<?php

namespace Database\Factories;

use App\Models\Commande;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Commande>
 */
class CommandeFactory extends Factory
{
    protected $model = Commande::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'code_commande' => 'CMD-'.$this->faker->unique()->numberBetween(1000, 9999),
            'produit' => $this->faker->randomElement(['Plastique', 'Compost', 'Métal', 'Papier']),
            'quantite' => $this->faker->numberBetween(1, 100),
            'statut' => 'en_attente',
            'client_id' => User::factory()->state(['role' => 'client']),
            'date_commande' => $this->faker->date(),
        ];
    }
}
