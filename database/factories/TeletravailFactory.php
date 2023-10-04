<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Teletravail>
 */
class TeletravailFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {

        $arr = array('20/02/2012', '25/01/2015', '30/02/2015');

            return [
                'raison' => "pour .....",
                'date' => $arr,
                "status" => "En attente",
                'is_deleted'=>0,
                'user_id'=>1,
        ];
    }
}
