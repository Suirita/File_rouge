<?php

namespace Modules\Interview\app\Exports;

use Modules\Interview\app\Models\Template;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class TemplatesExport implements FromQuery, WithMapping, WithHeadings, WithColumnFormatting
{
    /**
     * Define the query that fetches Templates with their relations.
     * Laravel-Excel will chunk this automatically.
     */
    public function query()
    {
        return Template::with(['types']);
    }

    /**
     * Convert each Template model into an array of cell values.
     */
    public function map($template): array
    {
        $typeTitles = $template->types->pluck('title')->implode(', ');

        return [
            $template->title,
            $typeTitles,
            // Format as Y-m-d H:i:s; Excel can parse this into a date/time cell
            $template->created_at->format('Y-m-d H:i:s'),
        ];
    }

    /**
     * The headings for the columns in row 1.
     */
    public function headings(): array
    {
        return [
            'Template Title',
            'Types',
            'Created At',
        ];
    }

    /**
     * Tell PhpSpreadsheet to treat column D as a date/time cell.
     */
    public function columnFormats(): array
    {
        return [
            'D' => NumberFormat::FORMAT_DATE_DATETIME,
        ];
    }
}
