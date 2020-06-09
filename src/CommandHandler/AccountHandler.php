<?php

declare(strict_types=1);

namespace Mhr\EventSourcePhp\CommandHandler;

use Mhr\EventSourcePhp\Command\AccountWasCreated;

class AccountHandler
{
    public function __invoke(AccountWasCreated $account)
    {
        die(get_class($account));
    }
}
