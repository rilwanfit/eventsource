<?php

declare(strict_types=1);

namespace Mhr\EventSourcePhp\Repository;

use Mhr\EventSourcePhp\Domain\AggregateRoot;
use Mhr\EventSourcePhp\Event\AccountWasCreatedEvent;
use Mhr\EventSourcePhp\EventStore\EventStore;
use Symfony\Component\Messenger\MessageBusInterface;

class AccountRepository
{
    private EventStore $eventStore;

    private MessageBusInterface $eventBus;

    public function __construct(EventStore $eventStore, MessageBusInterface $eventBus)
    {
        $this->eventStore = $eventStore;
        $this->eventBus = $eventBus;
    }

    public function save(AggregateRoot $aggregateRoot)
    {
        $this->eventStore->append('123', []);

        $domainEventStream = $aggregateRoot->uncommittedEvents();

        $this->eventBus->dispatch(new AccountWasCreatedEvent());
    }
}
