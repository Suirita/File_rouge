<?php

namespace Modules\Interview\app\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Interview extends Model
{
    protected $fillable = ['date'];

    public function participations(): HasMany
    {
        return $this->hasMany(Participation::class);
    }

    public function branches(): HasMany
    {
        return $this->hasMany(Branch::class);
    }
}
