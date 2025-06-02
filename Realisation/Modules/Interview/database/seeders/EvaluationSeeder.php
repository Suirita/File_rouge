<?php

namespace Modules\Interview\database\seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Modules\Interview\app\Models\Evaluator;
use Modules\Interview\app\Models\Interview;
use Modules\Interview\app\Models\Question;

class EvaluationSeeder extends Seeder
{
    public function run()
    {
        $interviews = Interview::where('status', 'completed')->get();

        $evaluators = Evaluator::all();

        $evaluations = [];

        foreach ($interviews as $interview) {
            $evaluatorgroup = ($interview->id % 2 === 0)
                ? [$evaluators[0], $evaluators[1]]
                : [$evaluators[2], $evaluators[3]];

            $questions = Question::whereHas('type.templates.interviews', function ($query) use ($interview) {
                $query->where('interviews.id', $interview->id);
            })->get();


            foreach ($evaluatorgroup as $eg) {
                foreach ($questions as $question) {
                    $evaluations[] = [
                        'evaluator_id' => $eg->id,
                        'interview_id' => $interview->id,
                        'question_id' => $question->id,
                        'score'      => $this->makeDummyScore(),
                        'remarks'    => $this->makeDummyRemark(),
                        'created_at' => $interview->date,
                        'updated_at' => $interview->date,
                    ];
                }
            }
        }

        if (!empty($evaluations)) {
            DB::table('evaluations')->insert($evaluations);
        }
    }

    /**
     * Dummy score generator (1–10).
     */
    protected function makeDummyScore(): int
    {
        return rand(1, 10);
    }

    /**
     * Dummy remarks generator.
     */
    protected function makeDummyRemark(): string
    {
        $remarks = [
            'Très clair et concis.',
            'Bonne profondeur de connaissances.',
            'Pourrait élaborer davantage sur les cas limites.',
            'Solide compréhension conceptuelle.',
            'Les réponses étaient trop brèves.',
        ];

        return $remarks[array_rand($remarks)];
    }
}
