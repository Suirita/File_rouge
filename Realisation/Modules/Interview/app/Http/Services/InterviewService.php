<?php

namespace Modules\Interview\app\Http\Services;

use Illuminate\Http\Request;
use Modules\Interview\app\Models\Template;
use Modules\Interview\app\Models\Candidate;
use Modules\Interview\app\Models\Interview;
use \Illuminate\Validation\ValidationException;

class InterviewService
{
    function AllInterviews()
    {
        $interviews = Interview::with(['candidate', 'evaluations.evaluator', 'evaluations.question', 'template.types.questions'])
            ->orderBy('date', 'desc')
            ->get();

        $templates = Template::all();

        return [
            'interviews' => $interviews,
            'templates' => $templates,
        ];
    }

    function addInterview($request)
    {
        Interview::create([
            'candidate_id' => $request->candidate,
            'template_id' => $request->template,
            'status' => $request->status,
            'date' => $request->date,
        ]);
    }

    function updateInterview($request, $id)
    {
        $interview = Interview::findOrFail($id);

        if ($interview->status == 'completed') {
            throw ValidationException::withMessages([
                'cannot_delete' => 'Cannot edit because the interview is already completed.',
            ]);
        }

        $interview->update([
            'template_id' => $request->template,
            'status' => $request->status,
            'date' => $request->date,
        ]);
    }

    function deleteInterview($id)
    {
        $interview = Interview::findOrFail($id);

        if ($interview->status == 'completed') {
            throw ValidationException::withMessages([
                'cannot_delete' => 'Cannot delete because the interview is already completed.',
            ]);
        }

        $interview::destroy($id);
    }
}
