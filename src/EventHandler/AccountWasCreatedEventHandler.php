<?php

declare(strict_types=1);

namespace Mhr\EventSourcePhp\EventHandler;

use Mhr\EventSourcePhp\Event\AccountWasCreatedEvent;

class AccountWasCreatedEventHandler
{
    public function __invoke(AccountWasCreatedEvent $accountWasCreatedEvent)
    {
        var_dump('AccountWasCreated id: ' . $accountWasCreatedEvent->id);
    }
}
