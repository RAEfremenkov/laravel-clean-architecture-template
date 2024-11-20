<?php

declare(strict_types=1);

namespace RAEfremenkov\CleanArch\Utils\Primitives;

interface INotification
{
    public function getId(): Guid;
}
