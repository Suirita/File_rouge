<?php

namespace Modules\Interview\app\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuestionEvaluation extends Model
{
    protected $fillable = [
        'question_response_id',
        'trainer_id',
        'score',
        'remarks',
    ];

    public function response(): BelongsTo
    {
        return $this->belongsTo(QuestionResponse::class, 'question_response_id');
    }

    public function trainer(): BelongsTo
    {
        return $this->belongsTo(Trainer::class);
    }
}
