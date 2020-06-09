<?php

declare(strict_types=1);

namespace Mhr\EventSourcePhp\Domain\Account;

use Mhr\EventSourcePhp\Domain\EventSourcedAggregateRoot;

class Account extends EventSourcedAggregateRoot
{
    public static function create()
    {
        return new self();
    }
}
