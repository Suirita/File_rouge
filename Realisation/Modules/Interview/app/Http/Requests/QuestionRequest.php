<?php

namespace Modules\Interview\app\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class QuestionRequest extends FormRequest
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
        $type = $this->route('type');
        $typeId = is_object($type) ? $type->id : $type;

        return [
            'type' => [
                'required',
                'string',
                'max:255',
                Rule::unique('types', 'title')->ignore($typeId),
            ],
            'questions' => 'required|array|min:1',
            'questions.*' => 'required|string|min:1|max:255',
        ];

        return $rules;
    }
}
