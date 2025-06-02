<?php

namespace Modules\Interview\database\seeders;

use Illuminate\Database\Seeder;
use Modules\Interview\App\Models\Answer;
use Modules\Interview\App\Models\Participation;
use Modules\Interview\App\Models\Question;

class AnswerSeeder extends Seeder
{
    public function run()
    {
        // 1) Grab all participations that are "completed", via the Eloquent model.
        //    This lets us access both interview_id and the created_at/updated_at directly.
        $completedParticipations = Participation::where('status', 'completed')->get();

        foreach ($completedParticipations as $participation) {
            // 2) For the current participation, find out which interview it belongs to:
            $interviewId = $participation->interview_id;

            // 3) We want all questions whose branch is attached to this same interview.
            //    We do that by looking at the pivot table 'interviews_branches':
            $questionsForThisInterview = Question::whereIn('branch_id', function ($query) use ($interviewId) {
                $query->select('branch_id')
                    ->from('interviews_branches')
                    ->where('interview_id', $interviewId);
            })->get();

            // 4) Now loop through each question, and create an answer row pointing to this participation.
            //    We also force created_at/updated_at of the answer to match the participation’s timestamps.
            foreach ($questionsForThisInterview as $question) {
                Answer::create([
                    'question_id'      => $question->id,
                    'participation_id' => $participation->id,
                    // Explicitly copy the participation timestamps
                    'created_at'       => $participation->created_at,
                    'updated_at'       => $participation->updated_at,
                ]);
            }
        }
    }
}
