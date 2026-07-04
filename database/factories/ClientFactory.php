<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\User;
use App\Models\Zone;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Client>
 */
class ClientFactory extends Factory
{
    protected $model = Client::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => User::factory()->make()->password,
            'remember_token' => \Illuminate\Support\Str::random(10),
            'telephone' => fake()->phoneNumber(),
            'zone_id' => Zone::factory(),
            'role' => 'client',
            'statut' => 'actif',
        ];
    }
}
