<?php

declare(strict_types=1);

namespace Mhr\EventSourcePhp\Domain;

class EventSourcedAggregateRoot implements AggregateRoot
{
    private array $uncommittedEvents = [];
    private int $playhead = -1;

    public function uncommittedEvents()
    {
        return $this->uncommittedEvents;
    }

    /**
     * Applies an event. The event is added to the AggregateRoot's list of uncommitted events.
     */
    public function apply($event): void
    {
        ++$this->playhead;
        $this->uncommittedEvents[] = [
            'aggregateRootId' => $this->aggregateRootId(),
            'playhead' => $this->playhead,
            'event' => $event
        ];
    }
}
