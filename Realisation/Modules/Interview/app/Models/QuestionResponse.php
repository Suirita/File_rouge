<?php

namespace Modules\Interview\app\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuestionResponse extends Model
{
    protected $fillable = ['question_id', 'participation_id', 'answer'];

    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }

    public function participation(): BelongsTo
    {
        return $this->belongsTo(Participation::class);
    }

    public function evaluation()
    {
        return $this->hasOne(QuestionEvaluation::class);
    }
}
