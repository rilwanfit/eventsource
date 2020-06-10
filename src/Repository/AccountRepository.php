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
        $domainEventStream = $aggregateRoot->uncommittedEvents();

        $this->eventStore->append('123', [
            [
                'playhead' => $domainEventStream[0]['playhead'],
                'metadata' => '{}',
                'payload' => '{}',
                'recorded_on' => '',
                'type' => 'type',
            ]
        ]);



        $this->eventBus->dispatch(new AccountWasCreatedEvent());
    }
}
