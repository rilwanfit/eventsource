<?php

declare(strict_types=1);

namespace Mhr\EventSourcePhp\EventStore;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Schema\Table;

class DBALEventStore implements EventStore
{
    private Connection $connection;

    private string $tableName = 'events';

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function append(string $id, $eventStream): void
    {
        $schemaManager = $this->connection->getSchemaManager();
        $table = $this->configureSchema($schemaManager->createSchema());

        if ($table !== null) {
            $schemaManager->createTable($table);
        }

        $this->connection->beginTransaction();
        try {
            foreach ($eventStream as $domainMessage) {
                $this->insertMessage($this->connection, $domainMessage);
            }

            $this->connection->commit();
        } catch (\Exception $e) {
            $this->connection->rollBack();
            throw $e;
        }
    }

    public function configureSchema(Schema $schema): ?Table
    {
        if ($schema->hasTable($this->tableName)) {
            return null;
        }

        return $this->configureTable($schema);
    }

    private function configureTable(Schema $schema): Table
    {
        $schema = $schema ?: new Schema();

        $table = $schema->createTable($this->tableName);

        $table->addColumn('id', 'integer', ['autoincrement' => true]);
        $table->addColumn('playhead', 'integer', ['unsigned' => true]);
        $table->addColumn('payload', 'text');
        $table->addColumn('metadata', 'text');
        $table->addColumn('recorded_on', 'string', ['length' => 32]);
        $table->addColumn('type', 'string', ['length' => 255]);

        $table->setPrimaryKey(['id']);
        $table->addUniqueIndex(['id', 'playhead']);

        return $table;
    }

    private function insertMessage(Connection $connection, $domainMessage)
    {
        $data = [
            'playhead' => [],
            'metadata' => json_encode([]),
            'payload' => json_encode([]),
            'recorded_on' => '',
            'type' => 'type',
        ];

        $connection->insert($this->tableName, $data);
    }
}
