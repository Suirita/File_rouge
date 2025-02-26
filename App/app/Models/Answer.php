<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    protected $fillable = ['question_id', 'enrollee_id', 'score'];

    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    public function enrollee()
    {
        return $this->belongsTo(Enrollee::class);
    }
}
