<?php

declare(strict_types=1);

namespace Mhr\EventSourcePhp\Command;

class AmountWasDepositedCommand
{
    public string $id = '123';
    public string $amount = '200';
}
