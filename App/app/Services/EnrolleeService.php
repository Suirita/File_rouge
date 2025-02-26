<?php

namespace App\Services;

use App\Models\Enrollee;
use Illuminate\Support\Facades\DB;

class EnrolleeService
{
  public function getAllEnrollees()
  {
    $enrollees = Enrollee::with('interview', 'answer')->get();
    return $enrollees;
  }

  public function countEnrollees()
  {
    $countEnrollees = Enrollee::count();
    return $countEnrollees;
  }

  public function countAcceptedEnrollees()
  {
    return Enrollee::whereDoesntHave('answer', function ($query) {
      $query->where('score', '<', 5);
    })->count();
  }

  public function countRejectedEnrollees()
  {
    return Enrollee::whereDoesntHave('answer', function ($query) {
      $query->where('score', '=', 1);
    })->count();
  }
}
