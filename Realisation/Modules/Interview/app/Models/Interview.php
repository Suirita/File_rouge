<?php

namespace Modules\Interview\app\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Interview extends Model
{
    protected $fillable = ['title'];

    public function participations(): HasMany
    {
        return $this->hasMany(Participation::class);
    }

    public function branches(): BelongsToMany
    {
        return $this->BelongsToMany(Branch::class, 'interviews_branches');
    }
}
