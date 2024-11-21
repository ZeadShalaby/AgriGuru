<?php

namespace App\Enums;

enum RoleEnums: string
{
    case Super = '0'; //0
    case Admin = '1';  //1
    case User = '3';   //3

    /**
     * Get all the enum Gender as an associative array.
     *
     * @return array
     */
    public static function Genders(): array
    {
        return [
            self::Super->name => '0',
            self::Admin->name => '1',
            self::User->name => '3',
        ];
    }
}
