<?php

declare(strict_types=1);

namespace Mhr\EventSourcePhp\Repository;

use Mhr\EventSourcePhp\EventStore\EventStore;

class AccountRepository
{
    public function __construct(EventStore $eventStore)
    {
    }
}
