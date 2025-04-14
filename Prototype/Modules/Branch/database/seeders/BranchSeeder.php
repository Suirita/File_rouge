<?php

namespace Modules\Branch\database\seeders;

use Illuminate\Database\Seeder;
use Modules\Branch\app\Models\Branch;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class BranchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $branches = [
            [
                'name' => 'Francais',
                'user_id' => 1
            ],
            [
                'name' => 'English',
                'user_id' => 1
            ],
            [
                'name' => 'Programmation',
                'user_id' => 1
            ],
            [
                'name' => 'Soft Skills',
                'user_id' => 1
            ],
            [
                'name' => 'Travail en équipe',
                'user_id' => 1
            ],
        ];

        foreach ($branches as $branch) {
            Branch::create($branch);
        }
    }
}
