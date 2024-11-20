<?php

declare(strict_types=1);

namespace RAEfremenkov\CleanArch\Utils\Primitives;

class DomainEvent implements INotification
{
    protected Guid $id;

    public function getId(): Guid
    {
        return $this->id;
    }
}
