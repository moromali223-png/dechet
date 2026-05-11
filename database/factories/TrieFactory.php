<?php

namespace Database\Factories;

use App\Models\Pesage;
use App\Models\Trie;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Trie>
 */
class TrieFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'type_dechet' => $this->faker->word(),
            'quantite_trier' => $this->faker->numberBetween(1, 100),
            'unite' => $this->faker->randomElement(['kg', 'litres']),
            'pesage_id' => Pesage::factory(),
        ];
    }
}
