<?php

namespace Modules\Interview\app\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Template extends Model
{
    protected $fillable = ['title'];

    public function interviews(): HasMany
    {
        return $this->hasMany(Interview::class);
    }

    public function Types(): BelongsToMany
    {
        return $this->BelongsToMany(Type::class, 'templates_types');
    }
}
