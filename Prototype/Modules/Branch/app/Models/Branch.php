<?php

namespace Modules\Branch\app\Models;

use Modules\Core\app\Models\User;
use Illuminate\Database\Eloquent\Model;
use Modules\Branch\app\Models\Question;

class Branch extends Model
{
    protected $fillable = [
        'name',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function questions()
    {
        return $this->hasMany(Question::class);
    }
}
