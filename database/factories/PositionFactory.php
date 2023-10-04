<?php

namespace Database\Factories;


use App\Models\Role;
use App\Models\Department;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Position>
 */
class PositionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'jobName' => "Admin",
            'status' => "active",
            'description' => "This is a Admin Post ..",
            'title' => "This is a Admin Post ..",
            'role_id' => Role::get('id')->random(),
            'department_id' => Department::get('id')->random(),
            'created_at' => now(),
            'updated_at' => now()
        ];
    }
}
