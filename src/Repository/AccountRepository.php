<?php

declare(strict_types=1);

namespace Mhr\EventSourcePhp\Repository;

use Mhr\EventSourcePhp\Domain\AggregateRoot;
use Mhr\EventSourcePhp\EventStore\EventStore;

class AccountRepository
{
    private EventStore $eventStore;

    public function __construct(EventStore $eventStore)
    {
        $this->eventStore = $eventStore;
    }

    public function save(AggregateRoot $aggregateRoot)
    {
        $domainEventStream = $aggregateRoot->uncommittedEvents();

        $this->eventStore->append('123', []);
        var_dump($domainEventStream);

        die();
    }
}
