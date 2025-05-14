<?php

namespace Modules\Interview\database\seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Modules\Interview\app\Models\Answer;

class AnswerSeeder extends Seeder
{
    public function run()
    {
        $questionIds = DB::table('questions')->pluck('id')->all();

        $participationIds = DB::table('participations')
            ->where('status', 'completed')
            ->pluck('id')
            ->all();

        $answers = [];

        foreach ($participationIds as $pid) {
            foreach ($questionIds as $qid) {
                $answers[] = [
                    'question_id'       => $qid,
                    'participation_id'  => $pid,
                ];
            }
        }


        foreach ($answers as $answer) {
            Answer::create($answer);
        }
    }
}
