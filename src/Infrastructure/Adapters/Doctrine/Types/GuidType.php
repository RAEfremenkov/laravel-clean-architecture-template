<?php

declare(strict_types=1);

namespace RAEfremenkov\CleanArch\Infrastructure\Adapters\Doctrine\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Exception\InvalidFormat;
use Doctrine\DBAL\Types\Exception\InvalidType;
use Doctrine\DBAL\Types\Type;
use RAEfremenkov\CleanArch\Utils\Primitives\Guid;
use ReflectionClass;

class GuidType extends Type
{
    const CUSTOM_GUID = 'custom_guid';

    public function getName()
    {
        return self::CUSTOM_GUID;
    }

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return $platform->getGuidTypeDeclarationSQL($column);
    }

    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): mixed
    {
        if ($value instanceof Guid) {
            return $value;
        }

        if (! is_string($value) || ! Guid::isValidGuid($value)) {
            throw InvalidFormat::new(
                $value,
                static::class,
                'string: length 36 characters'
            );
        }

        $dtoReflection = new ReflectionClass(Guid::class);

        /** @var Guid $guid */
        $guid = $dtoReflection->newInstanceWithoutConstructor();

        $valueProp = $dtoReflection->getProperty('value');

        $valueProp->setValue($guid, $value);

        return $guid;
    }

    public function convertToDatabaseValue(mixed $value, AbstractPlatform $platform): mixed
    {
        if ($value instanceof Guid) {
            return $value->getValue();
        }

        if (is_string($value) && Guid::isValidGuid($value)) {
            return $value;
        }

        throw InvalidType::new(
            $value,
            static::class,
            ['null', Guid::class],
        );
    }
}
