<?php

namespace  Modules\Interview\database\seeders;

use Illuminate\Database\Seeder;
use Modules\Interview\app\Models\Branch;

class BranchSeeder extends Seeder
{
    public function run()
    {
        $branches = [
            [
                'title' => 'French'
            ],
            [
                'title' => 'English'
            ],
            [
                'title' => 'Soft Skills'
            ],
            [
                'title' => 'Teamwork'
            ],
            [
                'title' => 'Front end'
            ],
            [
                'title' => 'Back end'
            ]
        ];

        foreach ($branches as $branch) {
            Branch::create($branch);
        }
    }
}
