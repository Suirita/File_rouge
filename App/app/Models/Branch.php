<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
  protected $fillable = ['name'];

  public function questions()
  {
    return $this->hasMany(Question::class);
  }

  public function interviews()
  {
    return $this->belongsToMany(Interview::class, 'interview_branch');
  }
}
