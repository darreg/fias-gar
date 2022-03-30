<?php

declare(strict_types=1);

namespace App\Auth\Domain\User\Entity;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;

final class MainRoleType extends StringType
{
    public const NAME = 'auth_user_main_role';

    public function convertToDatabaseValue($value, AbstractPlatform $platform): mixed
    {
        return $value instanceof MainRole ? $value->getName() : $value;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): ?MainRole
    {
        return !empty($value) ? new MainRole((string)$value) : null;
    }

    public function getName(): string
    {
        return self::NAME;
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }
}
