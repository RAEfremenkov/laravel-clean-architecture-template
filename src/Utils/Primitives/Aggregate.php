<?php

declare(strict_types=1);

namespace RAEfremenkov\CleanArch\Utils\Primitives;

abstract class Aggregate implements IAggregateRoot
{
    private array $domainEvents = [];

    public function getDomainEvents(): array
    {
        return $this->domainEvents;
    }

    public function clearDomainEvents(): void
    {
        $this->domainEvents = [];
    }

    protected function raiseDomainEvent(DomainEvent $domainEvent): void
    {
        $this->domainEvents[] = $domainEvent;
    }
}
