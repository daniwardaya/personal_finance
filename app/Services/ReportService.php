<?php
namespace App\Services;

use App\Repositories\TransactionRepository;

class ReportService
{
    protected $repository;

    public function __construct(TransactionRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Generate Monthly Report
     */
    public function generateMonthlyReport(int $userId, int $month, int $year): array
    {
        // Ambil transaksi dari repository
        $transactions = $this->repository->getMonthlyTransactions($userId, $month, $year);

        // Hitung total pemasukan dan pengeluaran
        $totalIncome  = $transactions->where('type', 'income')->sum('amount');
        $totalExpense = $transactions->where('type', 'expense')->sum('amount');
        $balance      = $totalIncome - $totalExpense;

        // Hitung kategori berdasarkan income dan expense
        $categories = $transactions
            ->groupBy('category')
            ->map(function ($group, $category) use ($totalIncome, $totalExpense) {
                $totalAmount = $group->sum('amount');
                $type        = $group->first()->type;

                // Pastikan totalIncome dan totalExpense tidak nol
                $percentage = $type === 'income' && $totalIncome > 0
                ? ($totalAmount / $totalIncome) * 100
                : ($type === 'expense' && $totalExpense > 0
                    ? ($totalAmount / $totalExpense) * 100
                    : 0);

                return [
                    'category'   => $category,
                    'amount'     => $totalAmount,
                    'percentage' => round($percentage, 2),
                ];
            })
            ->values()
            ->toArray();

        // Return hasil laporan
        return [
            'month'         => now()->month($month)->year($year)->format('F Y'),
            'total_income'  => $totalIncome,
            'total_expense' => $totalExpense,
            'balance'       => $balance,
            'categories'    => $categories,
        ];
    }

}