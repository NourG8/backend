<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Department;
use App\Models\Faq;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\FaqDepartment>
 */
class FaqDepartmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'department_id' => Department::get('id')->random(),
            'faq_id' => Faq::get('id')->random(),
            'created_at' => now(),
            'updated_at' => now()
        ];
    }
}
