<?php

namespace Database\Seeders;

use App\Models\Feature;
use App\Models\User;
use App\Models\UserFeature;
use Illuminate\Database\Seeder;

class UserFeatureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $features = Feature::all();
        $users = User::all();

        foreach ($features as $feature) {

            foreach ($users as $user) {

                UserFeature::firstOrCreate([
                    'feature_id' => $feature->id,
                    'user_id' => $user->id
                ]);
            }
        }
    }
}
