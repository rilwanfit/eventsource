<?php

declare(strict_types=1);

namespace Mhr\EventSourcePhp\Domain\Account;

use Mhr\EventSourcePhp\Domain\EventSourcedAggregateRoot;
use Mhr\EventSourcePhp\Event\AccountWasCreatedEvent;
use Mhr\EventSourcePhp\Event\AmountWasDepositedEvent;

class Account extends EventSourcedAggregateRoot
{
    private $id;

    public static function create(string $id)
    {
        $account =  new self();
        $account->id = $id;

        $account->apply(
            new AccountWasCreatedEvent($id)
        );

        return $account;
    }

    public function deposit(string $id, string $amount)
    {
        $this->apply(
            new AmountWasDepositedEvent($id, $amount)
        );
    }

    public function applyAmountWasDepositedEvent(AmountWasDepositedEvent $event)
    {
        echo __METHOD__;
    }

    public function aggregateRootId(): string
    {
        return (string) $this->id;
    }

    public static function instantiateForReconstitution(): self
    {
        return new static();
    }
}
