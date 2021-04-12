<?php declare(strict_types=1);

namespace App\Enum;

/**
 * @method static self ROLE_USER
 * @method static self ROLE_ADMIN
 */
class RoleEnum extends AbstractEnum
{
    public const ROLE_USER = 'ROLE_USER';
    public const ROLE_ADMIN = 'ROLE_ADMIN';

    protected static $_enums = [
        1 => self::ROLE_USER,
        2 => self::ROLE_ADMIN,
    ];
}
