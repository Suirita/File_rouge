<?php

namespace Modules\Interview\app\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EvaluationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'evaluations' => [
                'interviewId' => 'required|integer|exists:interviews,id',
                'evaluatorId' => 'required|integer|exists:evaluators,id',
                'questionId' => 'required|integer|exists:questions,id',
                'score' => 'required|integer|min:1|max:10',
                'remarks' => 'nullable|string|max:255',
            ],
        ];

        return $rules;
    }
}
