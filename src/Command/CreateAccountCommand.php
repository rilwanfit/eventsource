<?php

declare(strict_types=1);

namespace Mhr\EventSourcePhp\Command;

/**
 * This class containing only the fields which are necessary to create an account
 */
class CreateAccountCommand
{
    public string $id;

    public function __construct(string $id)
    {
        $this->id = $id;
    }
}
