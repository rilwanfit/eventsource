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
     * Initializes the aggregate using the given "history" of events.
     */
    public function initializeState($events): void
    {
        foreach ($events as $event) {
            ++$this->playhead;
            $this->handleRecursively($event);
        }
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
            'uuid' => $event->id,
            'metadata' => '{}',
            'payload' => $serializer->serialize($event, 'json'),
            'recorded_on' => '',
            'type' => get_class($event),
        ];
    }

    private function handleRecursively($event)
    {
        $this->handle($event);
    }

    private function handle($event)
    {
        $method = $this->getApplyMethod($event);

        if (!method_exists($this, $method)) {
            return;
        }

        $this->$method($event);
    }

    private function getApplyMethod($event)
    {
        $classParts = explode('\\', $event['type']);

        return 'apply'.end($classParts);
    }
}
