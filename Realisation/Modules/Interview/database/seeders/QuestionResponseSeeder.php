<?php

namespace Modules\Interview\database\seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class QuestionResponseSeeder extends Seeder
{
    public function run()
    {
        $questionIds = DB::table('questions')->pluck('id')->all();

        $participationIds = DB::table('participations')
            ->where('status', 'completed')
            ->pluck('id')
            ->all();

        $now = now();
        $rows = [];

        foreach ($participationIds as $pid) {
            foreach ($questionIds as $qid) {
                $rows[] = [
                    'question_id'       => $qid,
                    'participation_id'  => $pid,
                    'answer'            => $this->makeDummyAnswer($qid, $pid),
                    'created_at'        => $now,
                    'updated_at'        => $now,
                ];
            }
        }

        DB::table('question_responses')->insert($rows);
    }


    /**
     * Dummy answer generator.
     * You can replace this with Faker or any other logic.
     */
    protected function makeDummyAnswer(int $questionId, int $participationId): string
    {
        static $templates = [
            1 => 'Je suis {{name}}, passionné par le développement web.',
            2 => 'I am {{name}}, looking forward to learning more.',
            3 => 'I manage my time by setting clear daily goals.',
            4 => 'I manage stress by taking short breaks and prioritizing tasks.',
            5 => 'I use `for…of` loops or `array.forEach()` in JavaScript.',
        ];

        $tpl = $templates[$questionId] ?? 'Answer to Q' . $questionId;

        $name = 'Candidate' . $participationId;
        return str_replace('{{name}}', $name, $tpl);
    }
}
