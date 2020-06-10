<?php

declare(strict_types=1);

namespace Mhr\EventSourcePhp\EventHandler;

class AccountWasCreatedEventHandler
{
    public function __invoke()
    {
        die(get_class($this));
    }
}
