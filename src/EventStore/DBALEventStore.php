<?php

declare(strict_types=1);

namespace Mhr\EventSourcePhp\EventStore;

use Doctrine\DBAL\Connection;

class DBALEventStore implements EventStore
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }
}
