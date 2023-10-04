<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Position;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class PositionUserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'startDate' =>$this->faker->date($format='Y-m-d', $max='now'),
            'endDate' =>$this->faker->date($format='Y-m-d', $max='now'),
            'position_id' => Position::get('id')->random(),
            'user_id' => User::get('id')->random(),
            'created_at' => now(),
            'updated_at' => now()
        ];
    }
}
