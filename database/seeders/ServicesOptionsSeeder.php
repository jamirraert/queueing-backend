<?php

namespace Database\Seeders;

use App\Models\ServiceOptions;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ServicesOptionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $raw = file_get_contents(database_path('seeders/raw/ServiceOptions.json'));
        
        $options = json_decode($raw, true);

        foreach($options as $option) {
            ServiceOptions::firstOrCreate([
                'name' => $option['name'], 
                'letter' => $option['letter'],
                'start_number' => $option['start_number'],
                'status' => $option['status']
            ]);
        }
    }
}
