<?php
namespace Tests\Feature;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReportTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_generate_monthly_report()
    {
        $user  = User::factory()->create(); // Buat user baru
         // Login untuk mendapatkan token JWT
        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'password', // Pastikan sesuai dengan factory User
        ]);

        $token = $response->json('token');


        // Buat transaksi untuk user
        Transaction::factory()->count(3)->create([
            'user_id'  => $user->id,
            'type'     => 'income',
            'amount'   => 100000,
            'category' => 'Food',
            'date'     => now()->startOfMonth(),
        ]);

        // Kirim permintaan
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/reports/monthly', [
            'user_id' => $user->id,
            'month'   => now()->month,
            'year'    => now()->year,
        ]);
        

        // Pastikan respons sukses
        $response->assertStatus(200)
            ->assertJsonStructure([
                'month',
                'total_income',
                'total_expense',
                'balance',
                'categories' => [
                    '*' => ['category', 'amount', 'percentage'],
                ],
            ]);
    }

}