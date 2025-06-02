<?php

namespace Modules\Interview\app\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Candidate extends Model
{
    protected $fillable = ['name'];

    public function interviews(): HasMany
    {
        return $this->hasMany(Interview::class);
    }
}
