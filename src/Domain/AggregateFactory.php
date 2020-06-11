<?php

declare(strict_types=1);

namespace Mhr\EventSourcePhp\Domain;

final class AggregateFactory
{
    public function create(string $aggregateClass, $domainEventStream): EventSourcedAggregateRoot
    {
        $methodCall = sprintf('%s::%s', $aggregateClass, 'instantiateForReconstitution');
        $aggregate = call_user_func($methodCall);

        $aggregate->initializeState($domainEventStream);

        return $aggregate;
    }
}
