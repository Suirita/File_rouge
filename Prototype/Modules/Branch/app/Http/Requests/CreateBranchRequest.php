<?php

namespace Modules\Branch\app\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BranchRequest extends FormRequest
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
            'name' => 'required|string|max:255|unique:branches,name',
            'questions' => 'required|array|min:1', // Ensure it's an array and not empty
            'questions.*' => 'required|string|max:500', // Each question must be a valid string
        ];

        return $rules;
    }
}
