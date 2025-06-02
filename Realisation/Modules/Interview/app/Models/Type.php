<?php

namespace Modules\Interview\app\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Type extends Model
{
    protected $fillable = ['title'];

    public function templates(): BelongsToMany
    {
        return $this->BelongsToMany(Template::class, "templates_types");
    }

    public function questions(): HasMany
    {
        return $this->hasMany(Question::class);
    }
}
