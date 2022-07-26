<?php

namespace Database\Seeders;

use App\Models\Feature;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FeatureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $features = ['loan', 'ewallet', 'salary'];

        foreach ($features as $feature) {
            Feature::firstOrCreate([
                'name' => $feature
            ]);
        }
    }
}
