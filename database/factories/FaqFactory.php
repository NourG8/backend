<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Faq>
 */
class FaqFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $arr = array('add', 'nouveau', 'utilisateur');
        return [
            'title' => "Comment puis-je ajouter un nouveau utilisateur ?",
            'response' => "Vous devez deriger a la page utilisateur et puis vous devez cliquer sur 'Nouveau utilisateur'",
            'etat' => 1, // publié
            'tags' =>$arr,

        ];
    }
}
