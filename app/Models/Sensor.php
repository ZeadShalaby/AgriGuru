<?php

namespace App\Models;

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


    public function getSensorType()
    {
        $roles = [
            SensorEvents::Degree->value => 'Degree',
            SensorEvents::Humadity->value => 'Humadity',
            SensorEvents::Ldr->value => 'LDR',
            SensorEvents::Gas->value => 'Gas',
        ];

        return $roles[$this->role] ?? 'Unknown';
    }
}
