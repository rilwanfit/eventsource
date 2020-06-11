<?php

declare(strict_types=1);

namespace Mhr\EventSourcePhp\Event;

final class AccountWasCreatedEvent extends Event
{
    public string $id;

    public function __construct(string $id)
    {
        $this->id = $id;

        parent::__construct();
    }

    public function payload(): array
    {
        return [
            'id' => $this->id,
            'date' => $this->date()
        ];
    }
}
