<?php

namespace App\Repositories;

use App\Models\Budget;

class BudgetRepository
{
    public function updateBudget(int $userId, array $data): void
    {
        Budget::updateOrCreate(
            ['user_id' => $userId, 'category' => $data['category']],
            ['budget' => $data['budget']]
        );
    }

    public function createUserBudget(int $userId, array $data): void
    {
        Budget::create([
            'user_id'  => $userId,
            'category' => $data['category'],
            'budget'   => $data['budget'],
        ]);
    }
}