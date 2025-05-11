<?php

namespace Modules\Interview\app\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Trainer extends Model
{
    protected $fillable = ['name', 'email'];

    public function participations(): HasMany
    {
        return $this->hasMany(Participation::class);
    }

    public function evaluations(): HasMany
    {
        return $this->hasMany(QuestionEvaluation::class);
    }
}
