<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $raw = file_get_contents(database_path('seeders/raw/Role.json'));
        
        $roles = json_decode($raw, true);

        foreach($roles as $role) {
            Role::firstOrCreate(['name' => $role['name']]);
        }
    }
}
