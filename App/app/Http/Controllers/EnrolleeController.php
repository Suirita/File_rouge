<?php

namespace App\Http\Controllers;

use App\Models\Enrollee;
use Illuminate\Http\Request;
use App\Services\EnrolleeService;
use Inertia\Inertia;

class EnrolleeController extends Controller
{
  protected EnrolleeService $enrolleeService;

  public function __construct(EnrolleeService $enrolleeService)
  {
    $this->enrolleeService = $enrolleeService;
  }
  /**
   * Display a listing of the resource.
   */
  public function index()
  {
    return Inertia::render('Dashboard', [
      'enrollees' => $this->enrolleeService->getAllEnrollees(),
      'countEnrollees' => $this->enrolleeService->countEnrollees(),
      'countAcceptedEnrollees' => $this->enrolleeService->countAcceptedEnrollees(),
      'countRejectedEnrollees' => $this->enrolleeService->countRejectedEnrollees(),
    ]);
  }

  /**
   * Show the form for creating a new resource.
   */
  public function create()
  {
    //
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(Request $request)
  {
    //
  }

  /**
   * Display the specified resource.
   */
  public function show(Enrollee $enrollee)
  {
    //
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit(Enrollee $enrollee)
  {
    //
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, Enrollee $enrollee)
  {
    //
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(Enrollee $enrollee)
  {
    //
  }
}
