<?php

namespace Modules\Interview\app\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Question extends Model
{
    protected $fillable = ['branch_id', 'text'];

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function answers(): HasMany
    {
        return $this->hasMany(Answer::class);
    }
}
