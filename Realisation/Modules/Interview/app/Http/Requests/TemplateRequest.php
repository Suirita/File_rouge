<?php

namespace Modules\Interview\app\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TemplateRequest extends FormRequest
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
        $interview = $this->route('interview');
        $interviewId = is_object($interview) ? $interview->id : $interview;

        return [
            'title' => [
                'required',
                'string',
                'max:255',
                Rule::unique('interviews', 'title')->ignore($interviewId),
            ],
            'types' => 'required|array|min:1',
            'types.*' => 'required|integer|distinct',
        ];

        return $rules;
    }
}
