<?php

declare(strict_types=1);

namespace Mhr\EventSourcePhp\EventStore;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Driver\Statement;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Schema\Table;
use Exception;

class DBALEventStore implements EventStore
{
    private Connection $connection;

    private string $tableName = 'events';

    private $loadStatement;

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

    public function load($id)
    {
        $statement = $this->prepareLoadStatement();
        $statement->bindValue(1, $id);
        $statement->bindValue(2, 0);
        $statement->execute();

        $events = [];
        while ($row = $statement->fetch()) {
            $events[] = $row;
        }

        if (empty($events)) {
            throw new Exception(sprintf('EventStream not found for aggregate with id %s for table %s', $id, $this->tableName));
        }

        return $events;
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
        $table->addColumn('uuid', 'integer', ['unsigned' => true, 'notnull'=> false]);
        $table->addColumn('playhead', 'integer', ['unsigned' => true]);
        $table->addColumn('payload', 'text');
        $table->addColumn('metadata', 'text');
        $table->addColumn('recorded_on', 'string', ['length' => 32]);
        $table->addColumn('type', 'string', ['length' => 255]);

        $table->setPrimaryKey(['id']);
        $table->addUniqueIndex(['uuid', 'playhead']);

        return $table;
    }

    private function insertMessage(Connection $connection, $domainMessage)
    {
        $connection->insert($this->tableName, $domainMessage);
    }

    private function prepareLoadStatement(): Statement
    {
        if (null === $this->loadStatement) {
            $query = 'SELECT `uuid`, `playhead`, `metadata`, `payload`, `recorded_on`, `type`
                FROM '.$this->tableName.'
                WHERE uuid = ?
                AND playhead >= ?
                ORDER BY playhead ASC';
            $this->loadStatement = $this->connection->prepare($query);
        }

        return $this->loadStatement;
    }
}
