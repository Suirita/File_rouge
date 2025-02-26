<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Enrollee extends Model
{
  use HasFactory;
  protected $fillable = ['first_name', 'last_name'];

  public function interview()
  {
    return $this->hasOne(Interview::class);
  }

  public function answer()
  {
    return $this->hasOne(Answer::class);
  }
}
