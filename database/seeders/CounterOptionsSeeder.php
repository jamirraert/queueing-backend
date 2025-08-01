<?php

namespace Database\Seeders;

use App\Models\CounterOptions;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CounterOptionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $raw = file_get_contents(database_path('seeders/raw/CounterOptions.json'));
        
        $options = json_decode($raw, true);

        foreach($options as $option) {
            CounterOptions::firstOrCreate([
                'name' => $option['name'], 
                'status' => $option['status']
            ]);
        }
    }
}
