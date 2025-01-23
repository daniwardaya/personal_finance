<?php

namespace App\Services;

use App\Repositories\BudgetRepository;

class BudgetService
{
    protected BudgetRepository $repository;

    public function __construct(BudgetRepository $repository)
    {
        $this->repository = $repository;
    }

    public function updateUserBudget(int $userId, array $data): void
    {
        $this->repository->updateBudget($userId, $data);
    }
    public function createUserBudget(int $userId, array $data): void
    {
        $this->repository->createUserBudget($userId, $data);
    }
}