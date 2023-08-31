<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;


class CategoriesSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        for ($i = 1; $i <= 10; $i++) {
            DB::table('categories')->insert([
                'title' => $faker->word,
                'category_id' => $faker->randomElement([null, $faker->numberBetween(1, 5)]),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
