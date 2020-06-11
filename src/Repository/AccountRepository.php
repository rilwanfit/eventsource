<?php

declare(strict_types=1);

namespace Mhr\EventSourcePhp\Repository;

use Exception;
use Mhr\EventSourcePhp\Domain\Account\Account;
use Mhr\EventSourcePhp\Domain\AggregateFactory;
use Mhr\EventSourcePhp\Domain\AggregateRoot;
use Mhr\EventSourcePhp\Event\AccountWasCreatedEvent;
use Mhr\EventSourcePhp\EventStore\EventStore;
use RuntimeException;
use Symfony\Component\Messenger\MessageBusInterface;

class AccountRepository
{
    private EventStore $eventStore;

    private MessageBusInterface $eventBus;

    private AggregateFactory $aggregateFactory;

    public function __construct(EventStore $eventStore, MessageBusInterface $eventBus, AggregateFactory $aggregateFactory)
    {
        $this->eventStore = $eventStore;
        $this->eventBus = $eventBus;
        $this->aggregateFactory = $aggregateFactory;
    }

    public function save(AggregateRoot $aggregateRoot)
    {
        $domainEventStream = $aggregateRoot->uncommittedEvents();

        $this->eventStore->append('123', $domainEventStream);

        $this->eventBus->dispatch(new AccountWasCreatedEvent());
    }

    public function findById(string $id): Account
    {
        return $this->load($id);
    }

    public function load($id): AggregateRoot
    {
        try {
            $domainEventStream = $this->eventStore->load($id);
            return $this->aggregateFactory->create(Account::class, $domainEventStream);
        } catch (Exception $e) {
            throw new RuntimeException(sprintf("Aggregate with id '%s' not found", Account::class));
        }
    }
}
