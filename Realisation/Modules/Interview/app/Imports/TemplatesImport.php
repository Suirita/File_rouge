<?php

namespace Modules\Interview\app\Imports;

use Illuminate\Support\Facades\DB;
use Modules\Interview\app\Models\Template;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class TemplatesImport implements ToModel, WithHeadingRow, WithValidation
{
    /**
     * Validation rules for each row.
     */
    public function rules(): array
    {
        return [
            'template_title'  => 'required|string|max:255',
            'types'     => 'nullable|string',
        ];
    }

    public function customValidationMessages(): array
    {
        return [
            'template_title.required' => 'Every row must include a non-empty template title.',
        ];
    }

    /**
     * Process each row from Excel.
     *
     * @param  array  $row  // Keys: 'template_title', 'types', 'questions'
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return DB::transaction(function () use ($row) {
            $title = trim($row['template_title'] ?? '');
            if ($title === '') {
                return null; // Skip rows with no template
            }

            // Create or fetch the Template
            $template = Template::firstOrCreate(
                ['title' => $title]
            );

            // If there’s a “types” cell, split on commas
            if (!empty($row['types'])) {
                $typeTitles = array_filter(array_map('trim', explode(',', $row['types'])));
                foreach ($typeTitles as $typeTitle) {
                    // Create or fetch the Type for this Template
                    $type = $template->types()->firstOrCreate([
                        'title' => $typeTitle,
                    ]);
                }
            }

            return $template;
        });
    }
}
