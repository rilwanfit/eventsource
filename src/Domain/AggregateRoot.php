<?php


namespace Mhr\EventSourcePhp\Domain;

interface AggregateRoot
{
    public function uncommittedEvents();
}
