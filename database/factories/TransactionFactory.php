<?php
namespace Database\Factories;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TransactionFactory extends Factory
{
    protected $model = Transaction::class;

    public function definition()
    {
        return [
            'amount'      => $this->faker->numberBetween(1000, 100000),
            'category'    => $this->faker->randomElement(['Food', 'Transportation', 'Entertainment', 'Bills', 'Savings']),
            'type'        => $this->faker->randomElement(['income', 'expense']),
            'date'        => $this->faker->date('Y-m-d'), // Format tanggal sesuai kebutuhan
            'description' => $this->faker->sentence,
            'user_id'     => User::factory(), // Relasi dengan User
        ];
    }

}