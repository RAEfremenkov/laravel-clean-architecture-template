<?php

declare(strict_types=1);

namespace RAEfremenkov\CleanArch\Utils\Primitives;

interface INotificationHandler
{
    public function handle(INotification $notification): void;
}
