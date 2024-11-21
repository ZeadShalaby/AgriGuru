<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Sensor>
 */
class SensorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'temperature' => $this->faker->numberBetween(-10, 50),
            'humidity' => $this->faker->numberBetween(0, 100),
            'light' => $this->faker->numberBetween(0, 1000),
            'gas' => $this->faker->numberBetween(0.01, 0.9),
            'soil_moisture' => $this->faker->numberBetween(0, 100),
        ];
    }
}
