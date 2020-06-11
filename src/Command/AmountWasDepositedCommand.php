<?php

declare(strict_types=1);

namespace Mhr\EventSourcePhp\Command;

class AmountWasDepositedCommand
{
    public string $id;
    public string $amount;

    public function __construct(string $id, string $amount)
    {
        $this->id = $id;
        $this->amount = $amount;
    }
}
