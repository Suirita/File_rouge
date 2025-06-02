<?php

namespace Modules\Interview\app\Exports;

use Modules\Interview\app\Models\Type;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Collection;

class QuestionsExport implements FromCollection, WithHeadings
{
    /**
     * Fetch every Type (with its questions), transform into a simple Collection of rows.
     */
    public function collection()
    {
        $types = Type::with('questions')->get();

        // Use map() to build an array for each Type, then return a Collection of those arrays
        $rows = $types->map(function (Type $type) {
            $questionTitles = $type->questions
                ->pluck('title')
                ->implode(', ');

            return [
                'Type'      => $type->title,
                'Questions' => $questionTitles,
                'Created At' => $type->created_at->format('Y-m-d H:i:s'),
            ];
        });

        // Wrap in a new Collection so Laravel-Excel can iterate it
        return new Collection($rows);
    }

    /**
     * Column headings for the spreadsheet.
     */
    public function headings(): array
    {
        return [
            'Type',
            'Questions',
            'Created At',
        ];
    }
}
