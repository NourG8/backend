<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Permission;
use App\Models\Role;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PermissionRole>
 */
class PermissionRoleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'permission_id' => Permission::get('id')->random(),
            'role_id' => Role::get('id')->random(),
            'created_at' => now(),
            'updated_at' => now()
        ];
    }
}
