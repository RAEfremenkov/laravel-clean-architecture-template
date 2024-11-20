<?php

declare(strict_types=1);

namespace RAEfremenkov\CleanArch\Utils\Primitives;

interface IUnitOfWork
{
    public function saveChanges(): bool;
}
