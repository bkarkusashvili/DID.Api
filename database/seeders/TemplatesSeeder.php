<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class TemplatesSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        for ($i = 1; $i <= 10; $i++) {
            DB::table('templates')->insert([
                'title' => $faker->sentence,
                'old_url' => $faker->url,
                'new_url' => $faker->url,
                'size' => $faker->numberBetween(100, 1000),
                'image' => $faker->imageUrl(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
