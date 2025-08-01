<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        /**
         * @param seeder class
         */
        $this->call([
            RoleSeeder::class,
            CounterOptionsSeeder::class,
            ServicesOptionsSeeder::class
        ]);
    }
}
