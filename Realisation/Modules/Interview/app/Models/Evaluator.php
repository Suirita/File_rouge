<?php

namespace Modules\Interview\app\Models;

use Modules\Core\app\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Evaluator extends Model
{
    protected $fillable = ['name', 'user_id'];

    public function evaluations(): HasMany
    {
        return $this->hasMany(Evaluation::class);
    }

    public function user(): BelongsTo
    {
        return $this->BelongsTo(User::class);
    }
}
