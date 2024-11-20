<?php

declare(strict_types=1);

namespace RAEfremenkov\CleanArch\Utils\Primitives;

class Guid implements IValueObject
{
    private function __construct(
        private string $value
    ) {
        //
    }

    public static function create(): self
    {
        return new self(
            uuid_create()
        );
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public static function isValidGuid(string $guid): bool
    {
        return uuid_is_valid($guid);
    }

    public function __toString(): string
    {
        return $this->getValue();
    }
}
