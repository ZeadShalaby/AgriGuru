<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use App\Models\Sensor;
use App\Enums\RoleEnums;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // ? users
        $defUser = User::factory()->create([
            'name' => 'User',
            'email' => 'user@gmail.com',
            'password' => 'User10**',
            'phone' => '0124558155'
        ]);

        // ? admins
        $defAdmin = User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@ratecv.com',
            'role' => RoleEnums::Admin->value,
            'password' => 'Admin10**',
            'phone' => '0124558185'
        ]);

        // ? super admin
        $defSuperAdmin = User::factory()->create([
            'name' => 'SuperAdmin',
            'email' => 'super@gmail.com',
            'role' => RoleEnums::Super->value,
            'password' => 'Super10**',
            'phone' => '0124558785'
        ]);

        //? Create 10 Users
        $users = User::factory()
            ->count(9)
            ->create();
        $users->push($defUser);


        //? Create 1 Admins
        $admins = User::factory()
            ->admin()
            ->create();
        $admins->push($defAdmin);

        //? Create 1 Super Admins
        $supers = User::factory()
            ->superAdmin()
            ->create();
        $supers->push($defSuperAdmin);


        //? Create 10 Sensors  readings
        $sensors = Sensor::factory(20)->create();
    }
}
