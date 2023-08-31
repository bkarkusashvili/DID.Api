<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class SocialsSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        for ($i = 1; $i <= 10; $i++) {
            DB::table('socials')->insert([
                'title' => $faker->word,
                'type' => $faker->numberBetween(1, 5),
                'keywords' => $faker->words(3, true),
                'photos' => $faker->imageUrl(),
                'materials' => $faker->text,
                'suggestedPhoto' => $faker->imageUrl(),
                'text' => $faker->paragraph,
                'photoText' => $faker->paragraph,
                'comment' => $faker->paragraph,
                'user_id' => $faker->numberBetween(1, 20),
                'created_at' => now(),
                'updated_at' => now(),

            ]);
        }
    }
}
