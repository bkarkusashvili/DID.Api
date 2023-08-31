<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class FavoritesSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        for ($i = 1; $i <= 10; $i++) {
            DB::table('favorites')->insert([
                'user_id' => $faker->numberBetween(1, 20),
                'template_id' => $faker->numberBetween(1, 10),
                'category_id' => $faker->randomElement([null, $faker->numberBetween(1, 5)]),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
