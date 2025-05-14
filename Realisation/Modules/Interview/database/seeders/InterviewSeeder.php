<?php

namespace  Modules\Interview\database\seeders;

use Illuminate\Database\Seeder;
use Modules\Interview\app\Models\Interview;

class InterviewSeeder extends Seeder
{
    public function run()
    {
        $interviews = [
            [
                'title' => 'Interview 1',
            ],
            [
                'title' => 'Interview 2'
            ],
        ];

        foreach ($interviews as $interview) {
            Interview::create($interview);
        }
    }
}
