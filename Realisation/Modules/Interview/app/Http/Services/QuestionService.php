<?php

namespace Modules\Interview\app\Http\Services;

use Illuminate\Http\Request;
use Modules\Interview\app\Models\Type;
use Modules\Interview\app\Models\Question;
use Illuminate\Validation\ValidationException;

class QuestionService
{
    function AllQuestions()
    {
        // 1) Eager‐load questions + templates → interviews
        $types = Type::with(['questions', 'templates.interviews'])->get();

        // 2) For each Type, compute a `canModify` boolean
        $types->map(function (Type $type) {
            // Check if ANY of its templates has ≥1 Interview
            $anyTemplateHasInterview = $type->templates
                ->contains(fn($tpl) => $tpl->interviews->isNotEmpty());

            // If none of the templates has interviews, then canModify = true
            $type->canModify = ! $anyTemplateHasInterview;

            return $type;
        });

        return $types;
    }

    function addQuestions($request)
    {
        $type = Type::create([
            'title' => $request->type,
        ]);

        foreach ($request->questions as $questionTitle) {
            $type->questions()->create([
                'title' => $questionTitle,
            ]);
        }
    }

    function updateQuestion(Request $request, $id)
    {
        $type = Type::with('templates.interviews')->findOrFail($id);

        $anyTemplateHasInterview = $type->templates
            ->filter(fn($tpl) => $tpl->interviews->isNotEmpty())
            ->isNotEmpty();

        if ($anyTemplateHasInterview) {
            throw ValidationException::withMessages([
                'cannot_edit' => 'Cannot edit because one of the templates already has an interview.',
            ]);
        }


        $type->update(['title' => $request->type]);

        Question::where('type_id', $id)->delete();

        if (!empty($request->questions)) {
            foreach ($request->questions as $questionTitle) {
                Question::create([
                    'title' => $questionTitle,
                    'type_id' => $id,
                ]);
            }
        }
    }

    function deleteQuestion($id)
    {
        $type = Type::with('templates.interviews')->findOrFail($id);

        $anyTemplateHasInterview = $type->templates
            ->filter(fn($tpl) => $tpl->interviews->isNotEmpty())
            ->isNotEmpty();

        if ($anyTemplateHasInterview) {
            throw ValidationException::withMessages([
                'cannot_delete' => 'Cannot delete because one of the templates already has an interview.',
            ]);
        }

        Type::destroy($id);
    }
}
