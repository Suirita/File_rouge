<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Interview extends Model
{
  protected $fillable = ['enrollee_id'];

  public function enrollee()
  {
    return $this->belongsTo(Enrollee::class);
  }

  public function branches()
  {
    return $this->belongsToMany(Branch::class, 'interview_branch');
  }
}
