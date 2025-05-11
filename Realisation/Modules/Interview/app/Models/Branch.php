<?php

namespace Modules\Interview\app\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Branch extends Model
{
    protected $fillable = ['interview_id', 'title'];

    public function interview(): BelongsTo
    {
        return $this->belongsTo(Interview::class);
    }

    public function questions(): HasMany
    {
        return $this->hasMany(Question::class);
    }
}
