<?php

namespace Database\Factories;

use App\Models\User;
use App\Enums\RoleEnums;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return $this
     */
    public function unverified(): static
    {
        return $this->state(fn(array $attributes) => [
            'email_verified_at' => null,
        ]);
    }




    /**
     * Configure the factory with relationships.
     *
     * @return $this
     */
    public function configure()
    {

        return $this->afterCreating(function (User $user) {
            $img = ["/api/imageusers/users.png", "/api/imageusers/user1.png", "/api/imageusers/user2.png", "/api/imageusers/user3.png", "/api/imageusers/user5.png"];
            $increment = random_int(0, 3);
            $user->media()->create([
                'media' => $img[$increment],
            ]);
        });
    }


    // ?todo fake Super Admin
    public function superAdmin()
    {
        return $this->state(function (array $attributes) {
            return [
                'role' => RoleEnums::Super->value,
            ];
        });
    }


    // ?todo fake owner
    public function admin()
    {
        return $this->state(function (array $attributes) {
            return [
                'role' => RoleEnums::Admin->value,
            ];
        });
    }

    // ?todo fake user 
    public function user()
    {
        return $this->state(function (array $attributes) {
            return [
                'role' => RoleEnums::User->value,
            ];
        });
    }
}
