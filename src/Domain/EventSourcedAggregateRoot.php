<?php

declare(strict_types=1);

namespace Mhr\EventSourcePhp\Domain;

class EventSourcedAggregateRoot implements AggregateRoot
{
    private array $uncommittedEvents = [];

    public function uncommittedEvents()
    {
        return $this->uncommittedEvents;
    }
}
