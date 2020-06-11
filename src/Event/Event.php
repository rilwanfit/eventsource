<?php

declare(strict_types=1);

namespace Mhr\EventSourcePhp\Event;

use DateTimeImmutable;

abstract class Event
{
    private DateTimeImmutable $datetime;

    protected function __construct()
    {
        $this->datetime = new DateTimeImmutable();
    }

    public function date(): DateTimeImmutable
    {
        return $this->datetime;
    }

    abstract public function payload(): array;
}
