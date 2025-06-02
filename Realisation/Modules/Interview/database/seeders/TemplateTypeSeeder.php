<?php

namespace Modules\Interview\database\seeders;

use Illuminate\Database\Seeder;
use Modules\Interview\App\Models\Type;
use Modules\Interview\app\Models\Template;

class TemplateTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types   = Type::all();
        $templates = Template::all();

        foreach ($types as $idx => $type) {
            if ($idx == 1) {
                continue;
            }
            $type->templates()->syncWithoutDetaching($templates[0]->id);
        }

        foreach ($types as $type) {
            $type->templates()->syncWithoutDetaching($templates[1]->id);
        }
    }
}
