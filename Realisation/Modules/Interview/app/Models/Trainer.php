<?php

namespace Modules\Interview\app\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Trainer extends Model
{
  protected $fillable = ['name', 'email'];

  public function participations(): BelongsToMany
  {
    return $this->BelongsToMany(Participation::class, "trainers_participations");
  }

  public function evaluations(): HasMany
  {
    return $this->hasMany(Evaluation::class);
  }
}
