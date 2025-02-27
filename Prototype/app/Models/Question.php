<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
  protected $fillable = ['content', 'branch_id'];

  public function branch()
  {
    return $this->belongsTo(Branch::class);
  }
}
