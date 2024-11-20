<?php

declare(strict_types=1);

namespace RAEfremenkov\CleanArch\Utils\Primitives;

interface IAggregateRoot extends IEntity
{
    /**
     * @return array<int, DomainEvent>
     */
    public function getDomainEvents(): array;

    public function clearDomainEvents(): void;
}
