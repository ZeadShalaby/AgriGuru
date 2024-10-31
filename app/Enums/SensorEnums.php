<?php

namespace App\Enums;

enum SensorEnums: string
{
    case Degree = '1';

    case Humadity = '2';

    case Soil = '3';

    case Ldr = '4';

    case Gas = '5';

    /**
     * Get all the enum Gender as an associative array.
     *
     * @return array
     */
    public static function Genders(): array
    {
        return [
            self::Degree->name => '1',
            self::Humadity->name => '2',
            self::Soil->name => '3',
            self::Ldr->name => '4',
            self::Gas->name => '5',
        ];
    }
}