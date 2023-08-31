<?php

namespace Database\Seeders;


use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class SitesSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        for ($i = 1; $i <= 10; $i++) {
            DB::table('sites')->insert([
                'template_id' => $faker->numberBetween(1, 10),
                'user_id' => $faker->numberBetween(1, 20),
                'category_id' => $faker->numberBetween(1, 5),
                'data' => null, // 30% chance of being null
                'status' => $faker->randomElement(['draft', 'pending', 'active']),
                'image' => $faker->imageUrl(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}