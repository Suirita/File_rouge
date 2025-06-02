<?php

namespace Modules\Interview\app\Http\Services;

use Illuminate\Http\Request;
use Modules\Interview\app\Models\Type;
use Modules\Interview\app\Models\Template;
use \Illuminate\Validation\ValidationException;

class TemplateService
{
    function AllTemplates()
    {
        $templates = Template::with('Types', 'interviews')->get();

        // Compute `canModify` for each template
        $templates->map(function (Template $template) {
            // Check if ANY has ≥1 Interview
            $HasInterview = $template->interviews->isNotEmpty();

            // If none has interviews, then canModify = true
            $template->canModify = ! $HasInterview;

            return $template;
        });

        $types = Type::all();
        return [
            'templates' => $templates,
            'types'   => $types,
        ];
    }

    function addTemplates($request)
    {
        $template = Template::create([
            'title' => $request->title,
        ]);

        foreach ($request->types as $typeId) {
            $template->Types()->attach($typeId);
        }
    }

    function updateTemplate(Request $request, $id)
    {
        $template = Template::findOrFail($id);

        if ($template->interviews->isNotEmpty()) {
            throw ValidationException::withMessages([
                'cannot_edit' => 'Cannot edit because one of the interviews already has a template.',
            ]);
        }

        $template->update(['title' => $request->title]);

        $template->Types()->detach();

        if (!empty($request->types)) {
            $template->Types()->attach($request->types);
        }
    }

    function deleteTemplate($id)
    {
        $template = Template::findOrFail($id);

        if ($template->interviews->isNotEmpty()) {
            throw ValidationException::withMessages([
                'cannot_delete' => 'Cannot delete because one of the interviews already has a template.',
            ]);
        }

        Template::destroy($id);
    }
}
