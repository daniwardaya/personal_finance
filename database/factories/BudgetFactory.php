<?php

namespace Database\Factories;

use App\Models\Budget;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class BudgetFactory extends Factory
{
    protected $model = Budget::class;

    public function definition()
    {
        return [
            'user_id'  => User::factory(), // Relasi dengan user
            'category' => $this->faker->word, // Kategori
            'budget'   => $this->faker->numberBetween(100000, 1000000), // Nilai anggaran
        ];
    }
}