<?php

namespace Database\Factories;

use App\Models\Enrollee;
use Illuminate\Database\Eloquent\Factories\Factory;

class EnrolleeFactory extends Factory
{
  /**
   * Define the model's default state.
   *
   * @return array
   */
  public function definition()
  {
    return [
      'first_name' => $this->faker->firstName(),
      'last_name' => $this->faker->lastName(),
    ];
  }
}
