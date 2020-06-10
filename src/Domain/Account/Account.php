<?php

declare(strict_types=1);

namespace Mhr\EventSourcePhp\Domain\Account;

use Mhr\EventSourcePhp\Domain\EventSourcedAggregateRoot;
use Mhr\EventSourcePhp\Event\AccountWasCreatedEvent;

class Account extends EventSourcedAggregateRoot
{
    private $id;

    public static function create()
    {
        $account =  new self();
        $account->id = '123';

        $account->apply(
            new AccountWasCreatedEvent()
        );

        return $account;
    }

    public function aggregateRootId(): string
    {
        return (string) $this->id;
    }
}
