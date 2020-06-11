<?php

declare(strict_types=1);

namespace Mhr\EventSourcePhp\Event;

final class AmountWasDepositedEvent extends Event
{
    public string $id = '123';

    public string $amount = '200';

    public function payload(): array
    {
        return [
            'id' => $this->id,
            'amount' => $this->amount
        ];
    }
}
