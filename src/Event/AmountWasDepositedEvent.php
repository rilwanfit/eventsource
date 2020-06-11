<?php

declare(strict_types=1);

namespace Mhr\EventSourcePhp\Event;

final class AmountWasDepositedEvent extends Event
{
    public string $id;

    public string $amount;

    public function __construct(string $id, string $amount)
    {
        $this->id = $id;
        $this->amount = $amount;

        parent::__construct();
    }

    public function payload(): array
    {
        return [
            'id' => $this->id,
            'amount' => $this->amount,
            'date' => $this->date()
        ];
    }
}
