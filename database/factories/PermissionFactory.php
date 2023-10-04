<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Permission>
 */
class PermissionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'namePermession' => "Edit user",
            'description' => "Edit user description ...",
            'code' => "2545sd51z",
            'created_at' => now(),
            'updated_at' => now()
        ];
    }
}
