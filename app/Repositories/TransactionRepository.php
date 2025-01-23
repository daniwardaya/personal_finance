<?php
namespace App\Repositories;

use App\Models\Transaction;

class TransactionRepository
{
    public function getAllByUserId($userId)
    {
        return Transaction::where('user_id', $userId)->get();
    }

    public function findById($id, $userId)
    {
        // Menggunakan first() agar tidak terjadi exception jika data tidak ditemukan
        return Transaction::where('user_id', $userId)
            ->where('id', $id)
            ->first();
    }

    public function create(array $data)
    {
        return Transaction::create($data);
    }

    public function update(Transaction $transaction, array $data)
    {
        return $transaction->update($data);
    }

    public function delete(Transaction $transaction)
    {
        return $transaction->delete();
    }

    public function getMonthlyTransactions(int $userId, int $month, int $year)
    {
        return Transaction::where('user_id', $userId)
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->get();
    }

}