<?php

namespace App\Models;

use App\Enums\SensorEnums;
use App\Events\SensorEvents;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Sensor extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'temperature',
        'humidity',
        'light',
        'gas',
        'soil_moisture',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'created_at',
        'updated_at'
    ];


    public static function getSensorType($role)
    {
        $roles = [
            SensorEnums::Degree->value => 'temperature',
            SensorEnums::Humadity->value => 'humidity',
            SensorEnums::Ldr->value => 'light',
            SensorEnums::Gas->value => 'gas',
            SensorEnums::Soil->value => 'soil_moisture',

        ];

        return $roles[$role] ?? 'Unknown';
    }
}
