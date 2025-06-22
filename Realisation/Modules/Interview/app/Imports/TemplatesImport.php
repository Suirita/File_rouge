<?php

namespace Modules\Interview\app\Imports;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Modules\Interview\app\Models\Template;
use Modules\Interview\app\Models\Type;
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
            'template_title' => 'required|string|max:255',
            'types'          => 'required|string',
            'created_at'     => 'nullable|date',
        ];
    }

    public function customValidationMessages(): array
    {
        return [
            'template_title.required' => 'Every row must include a non-empty template title.',
            'types.required'          => 'Every row must include at least one type (comma-separated).',
            'created_at.date'         => 'If provided, created_at must be a valid date.',
        ];
    }

    /**
     * Process each row from Excel.
     *
     * @param  array  $row  Keys: 'template_title', 'types', 'created_at'
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return DB::transaction(function () use ($row) {
            // Normalize and trim the incoming title.
            $title = trim($row['template_title'] ?? '');

            // Parse created_at if provided, otherwise leave null.
            $providedCreatedAt = null;
            if (!empty($row['created_at'])) {
                $providedCreatedAt = Carbon::parse($row['created_at'])->toDateTimeString();
            }

            // Look up an existing Template by title, or create a new one if it doesn't exist.
            $template = Template::firstOrCreate(
                ['title' => $title],
                array_filter([
                    'created_at' => $providedCreatedAt,
                ], function ($value) {
                    return $value !== null;
                })
            );

            // Handle the "types" column. Each type must already exist.
            $typeTitles = array_filter(
                array_map('trim', explode(',', $row['types']))
            );

            $typeIds = [];
            foreach ($typeTitles as $typeTitle) {
                // This will throw ModelNotFoundException if no Type with that title exists
                $type = Type::where('title', $typeTitle)->firstOrFail();
                $typeIds[] = $type->id;
            }

            // Associate those existing types with this template.
            $template->types()->syncWithoutDetaching($typeIds);

            return $template;
        });
    }
}
