<?php

declare(strict_types=1);

namespace RAEfremenkov\CleanArch\Utils\Primitives;

interface IEntity
{
    public function getId(): Guid;
}
