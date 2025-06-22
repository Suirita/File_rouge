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
        $template = $this->route('template');
        $templateId = is_object($template) ? $template->id : $template;

        return [
            'title' => [
                'required',
                'string',
                'max:255',
                Rule::unique('templates', 'title')->ignore($templateId),
            ],
            'types' => 'required|array|min:1',
            'types.*' => 'required|integer|distinct',
        ];

        return $rules;
    }
}
