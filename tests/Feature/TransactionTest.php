<?php
namespace Tests\Feature;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TransactionTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    /** @test */
    public function user_can_create_transaction()
    {
        $user  = User::factory()->create();                        // Membuat user
         // Login untuk mendapatkan token JWT
        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'password', // Pastikan sesuai dengan factory User
        ]);

        $token = $response->json('token');


        $data = [
            'amount'      => 100,
            'category'    => 'Food',
            'type'        => 'expense',
            'date'        => '2025-01-21',
            'description' => 'Lunch',
            'user_id'     => $user->id, // Tambahkan user_id
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/transactions', $data);

        $response->assertStatus(201)
            ->assertJsonStructure(['data' => ['id', 'amount', 'category', 'type', 'date', 'description']]);
    }

    /** @test */
    public function test_transaction_creation_fails_with_invalid_category()
    {
        $user  = User::factory()->create();                        // Buat user baru
         // Login untuk mendapatkan token JWT
        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'password', // Pastikan sesuai dengan factory User
        ]);

        $token = $response->json('token');


        $data = [
            'amount'      => 1000,
            'category'    => 'InvalidCategory', // Kategori tidak valid
            'type'        => 'expense',
            'date'        => '2025-01-21',
            'description' => 'Lunch',
            'user_id'     => $user->id, // Gunakan ID dari user yang telah dibuat
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/transactions', $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['category']); // Validasi gagal pada kategori
    }

    /** @test */
    public function user_can_retrieve_transactions()
    {
        $user  = User::factory()->create(); // Buat user baru
         // Login untuk mendapatkan token JWT
        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'password', // Pastikan sesuai dengan factory User
        ]);

        $token = $response->json('token');


        Transaction::factory()->count(5)->create(['user_id' => $user->id]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/transactions');

        $response->assertStatus(200)
            ->assertJsonStructure(['data' => [['id', 'amount', 'category', 'type', 'date', 'description']]]);
    }

    /** @test */
    public function user_can_update_transaction()
    {
        $user  = User::factory()->create(); // Buat user baru
         // Login untuk mendapatkan token JWT
        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'password', // Pastikan sesuai dengan factory User
        ]);

        $token = $response->json('token');


        $transaction = Transaction::factory()->create(['user_id' => $user->id]);

        $updatedData = [
            'amount'   => 300000,
            'category' => 'Transportation',
            'type'     => 'income',
            'date'     => '2025-01-22', // Tambahkan field 'date'
            'user_id'  => $user->id,
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->putJson("/api/transactions/{$transaction->id}", $updatedData);

        $response->assertStatus(200)
            ->assertJson(['message' => 'Transaction updated successfully.']);
    }

    /** @test */
    public function user_can_delete_transaction()
    {
        $user  = User::factory()->create(); // Buat user baru
         // Login untuk mendapatkan token JWT
        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'password', // Pastikan sesuai dengan factory User
        ]);

        $token = $response->json('token');


        $transaction = Transaction::factory()->create(['user_id' => $user->id]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->deleteJson("/api/transactions/{$transaction->id}");

        $response->assertStatus(200)
            ->assertJson(['message' => 'Transaction deleted successfully.']);
    }
}