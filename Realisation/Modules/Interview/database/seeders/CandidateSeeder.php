<?php

namespace Modules\Interview\database\seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class CandidateSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create('en-US');

        $candidates = [];

        for ($i = 0; $i < 40; $i++) {
            // Generate a random first + last name
            $name = $faker->firstName() . ' ' . $faker->lastName();

            // Half get 2023-09-01, half get 2024-09-01
            $timestamp = $i < 20
                ? '2023-01-01 00:00:00'
                : '2024-01-01 00:00:00';

            $candidates[] = [
                'name'       => $name,
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ];
        }

        // Bulk insert all 40 in one go
        DB::table('candidates')->insert($candidates);
    }
}
