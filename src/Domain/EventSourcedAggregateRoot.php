<?php

declare(strict_types=1);

namespace Mhr\EventSourcePhp\Domain;

use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

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

        $encoders = [new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];

        $serializer = new Serializer($normalizers, $encoders);


        $this->uncommittedEvents[] = [
            'playhead' => $this->playhead,
            'metadata' => '{}',
            'payload' => $serializer->serialize($event, 'json'),
            'recorded_on' => '',
            'type' => 'type',
        ];
    }
}
