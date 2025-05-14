<?php

namespace Modules\Interview\app\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Evaluation extends Model
{
    protected $fillable = [
        'answer_id',
        'trainer_id',
        'score',
        'remarks',
    ];

    public function answer(): BelongsTo
    {
        return $this->belongsTo(Answer::class);
    }

    public function trainer(): BelongsTo
    {
        return $this->BelongsTo(Trainer::class);
    }
}
