<?php
namespace Database\Factories;
use Illuminate\Database\Eloquent\Factories\Factory;

class AccountFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->company() . ' Account', // Nama akun unik
            'balance' => $this->faker->numberBetween(0, 10000000), // Saldo acak
            'description' => $this->faker->optional()->sentence(), // Deskripsi opsional
        ];
    }
}
