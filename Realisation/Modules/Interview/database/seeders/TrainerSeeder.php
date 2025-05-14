<?php

namespace Modules\Interview\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Interview\App\Models\Trainer;

class TrainerSeeder extends Seeder
{
    public function run()
    {
        $trainers = [
            ['name' => 'Alice Martin',   'email' => 'alice@example.com'],
            ['name' => 'Bob Dupont',      'email' => 'bob@example.com'],
            ['name' => 'Charlie Durand',  'email' => 'charlie@example.com'],
            ['name' => 'Dana Leroy',      'email' => 'dana@example.com'],
        ];

        foreach ($trainers as $data) {
            Trainer::create($data);
        }
    }
}
