<?php

namespace Modules\Interview\app\Http\Services;

use Illuminate\Http\Request;
use Modules\Interview\app\Models\Question;
use Modules\Interview\app\Models\Evaluator;
use Modules\Interview\app\Models\Interview;
use Modules\Interview\app\Models\Evaluation;

class EvaluationService
{
    public function addEvaluation(Request $request)
    {
        foreach ($request->all() as $evaluation) {
            $evaluators = Evaluator::all();

            foreach ($evaluators as $e) {
                if ($e->id == $evaluation['evaluatorId']) {
                    return;
                }
            }

            $interview = Interview::findOrFail($evaluation['interviewId']);

            $question = Question::findOrFail($evaluation['questionId']);

            $evaluator = Evaluator::findOrFail($evaluation['evaluatorId']);

            $newEvaluation = new Evaluation([
                'interview_id' => $interview->id,
                'question_id' => $question->id,
                'evaluator_id' => $evaluator->id,
                'score' => $evaluation['score'],
                'remarks' => $evaluation['remarks'],
            ]);

            if ($interview->status !== 'completed') {
                $interview->status = 'completed';
            }

            $interview->save();
            $newEvaluation->save();
        }
    }
}
