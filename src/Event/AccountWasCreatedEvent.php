<?php

declare(strict_types=1);

namespace Mhr\EventSourcePhp\Event;

final class AccountWasCreatedEvent extends Event
{
    public string $id = '123';

    public function payload(): array
    {
        return [
            'id' => $this->id,
            'date' => $this->date()
        ];
    }
}
