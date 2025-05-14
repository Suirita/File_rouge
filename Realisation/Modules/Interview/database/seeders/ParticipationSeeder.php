<?php

namespace  Modules\Interview\database\seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;
use Modules\Interview\App\Models\Participation;

class ParticipationSeeder extends Seeder
{
    public function run()
    {
        // 8 completed, 11 pending, 1 absent (total 20)
        $statuses = Collection::make()
            ->push(...array_fill(0, 8, 'completed'))
            ->push(...array_fill(0, 11, 'pending'))
            ->push('absent')
            ->values();

        // Map into an array of row-data
        $participations = $statuses->map(function ($status, $i) {
            return [
                'candidate_id' => $i + 1,
                'interview_id' => 1,
                'status'       => $status,
                'created_at'   => now(),
                'updated_at'   => now(),
            ];
        })->toArray();

        foreach ($participations as $participation) {
            Participation::insert($participation);
        }
    }
}
