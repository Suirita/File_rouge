<?php

namespace Modules\Interview\app\Imports;

use Illuminate\Support\Facades\DB;
use Modules\Interview\app\Models\Type;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class QuestionsImport implements ToModel, WithHeadingRow, WithValidation
{
    /**
     * Validation rules for each row.
     *
     * Ensure the “type” column is present and non-empty,
     * and that “questions” (if provided) is a string.
     */
    public function rules(): array
    {
        return [
            'type'      => 'required|string|max:255',
            'questions' => 'nullable|string',
        ];
    }

    /**
     * Custom messages for validation failures.
     */
    public function customValidationMessages(): array
    {
        return [
            'type.required' => 'Each row must have a non-empty type title.',
        ];
    }

    /**
     * Process a single row from the Excel sheet.
     *
     * @param  array  $row  // Keys: 'type', 'questions'
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return DB::transaction(function () use ($row) {
            // Trim and validate the “type” column again (just in case)
            $typeTitle = trim($row['type'] ?? '');
            if ($typeTitle === '') {
                return null; // Skip if somehow blank
            }

            // Create or fetch an existing Type by title
            $type = Type::firstOrCreate(
                ['title' => $typeTitle]
            );

            // If there are comma-separated questions, attach them
            if (!empty($row['questions'])) {
                // Split on commas, trim each, filter out empties
                $questionTitles = array_filter(
                    array_map('trim', explode(',', $row['questions']))
                );

                foreach ($questionTitles as $questionTitle) {
                    // For each question, firstOrCreate under this Type
                    if ($questionTitle === '') {
                        continue;
                    }
                    $type->questions()->firstOrCreate([
                        'title' => $questionTitle,
                    ]);
                }
            }

            return $type;
        });
    }
}
