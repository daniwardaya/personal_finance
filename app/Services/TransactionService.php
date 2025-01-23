<?php
namespace App\Services;

use App\Repositories\TransactionRepository;
use Illuminate\Support\Facades\Cache;

class TransactionService
{
    protected $repository;

    public function __construct(TransactionRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getRepository()
    {
        return $this->repository;
    }

    public function getAllTransactions($userId)
    {
        return Cache::remember("transactions_user_{$userId}", 600, function () use ($userId) {
            return $this->repository->getAllByUserId($userId);
        });
    }

    public function createTransaction(array $data)
    {
        Cache::forget("transactions_user_{$data['user_id']}");
        return $this->repository->create($data);
    }

    public function updateTransaction($transaction, array $data)
    {
        Cache::forget("transactions_user_{$transaction->user_id}");
        return $this->repository->update($transaction, $data);
    }

    public function deleteTransaction($transaction)
    {
        Cache::forget("transactions_user_{$transaction->user_id}");
        return $this->repository->delete($transaction);
    }
}