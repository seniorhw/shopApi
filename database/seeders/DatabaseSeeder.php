<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        $this->call(
            [
                MenuSeeder::class,
                PermissionSeeder::class,
                UserSeeder::class,
                CategorySeeder::class,
                GoodFactorySeeder::class,
                CitySeeder::class
            ]
        );
    }
}
