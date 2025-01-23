<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Budget;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BudgetTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_update_budget()
    {
        // Buat user dan token
        $user = User::factory()->create();
       // Login untuk mendapatkan token JWT
        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'password', // Pastikan sesuai dengan factory User
        ]);

        $token = $response->json('token');


        // Data anggaran awal
        $initialBudget = Budget::factory()->create([
            'user_id'  => $user->id,
            'category' => 'Food',
            'budget'   => 2000000,
        ]);

        // Data yang akan diperbarui
        $updatedBudgetData = [
            'category' => 'Food',
            'budget'   => 3000000,
        ];

        // Lakukan permintaan PUT ke endpoint
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->putJson("/api/budgets/{$user->id}", $updatedBudgetData);

        // Pastikan respons sukses
        $response->assertStatus(200)
                 ->assertJson(['message' => 'Anggaran berhasil diperbarui.']);

        // Pastikan data di database telah diperbarui
        $this->assertDatabaseHas('budgets', [
            'user_id'  => $user->id,
            'category' => 'Food',
            'budget'   => 3000000,
        ]);
    }

    /** @test */
    public function unauthenticated_user_cannot_update_budget()
    {
        // Buat user dan anggaran
        $user = User::factory()->create();
        Budget::factory()->create([
            'user_id'  => $user->id,
            'category' => 'Food',
            'budget'   => 2000000,
        ]);

        // Data yang akan diperbarui
        $updatedBudgetData = [
            'category' => 'Food',
            'budget'   => 3000000,
        ];

        // Lakukan permintaan PUT tanpa token
        $response = $this->putJson("/api/budgets/{$user->id}", $updatedBudgetData);

        // Pastikan respons gagal dengan kode 401 (Unauthorized)
        $response->assertStatus(401);
    }

    /** @test */
    public function cannot_update_budget_with_invalid_data()
    {
        // Buat user dan token
        $user = User::factory()->create();
        // Login untuk mendapatkan token JWT
        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'password', // Pastikan sesuai dengan factory User
        ]);

        $token = $response->json('token');


        // Data yang tidak valid (tidak ada budget)
        $invalidBudgetData = [
            'category' => 'Food',
        ];

        // Lakukan permintaan PUT dengan data tidak valid
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->putJson("/api/budgets/{$user->id}", $invalidBudgetData);

        // Pastikan respons gagal dengan kode 422 (Unprocessable Entity)
        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['budget']);
    }

    /** @test */
    public function user_can_store_budget()
    {
        // Buat user dan token
        $user = User::factory()->create();
        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $token = $response->json('token');

        // Data anggaran baru
        $newBudgetData = [
            'category' => 'Food',
            'budget'   => 1500000,
        ];

        // Lakukan permintaan POST ke endpoint
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson("/api/budgets/{$user->id}", $newBudgetData);

        // Pastikan respons sukses
        $response->assertStatus(200)
                 ->assertJson(['message' => 'Anggaran berhasil ditambahkan.']);

        // Pastikan data di database telah ditambahkan
        $this->assertDatabaseHas('budgets', [
            'user_id'  => $user->id,
            'category' => 'Food',
            'budget'   => 1500000,
        ]);
    }

    /** @test */
    public function cannot_store_budget_with_invalid_data()
    {
        // Buat user dan token
        $user = User::factory()->create();
        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $token = $response->json('token');

        // Data yang tidak valid
        $invalidBudgetData = [
            'category' => 'InvalidCategory',
            'budget'   => -1000,
        ];

        // Lakukan permintaan POST dengan data tidak valid
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson("/api/budgets/{$user->id}", $invalidBudgetData);

        // Pastikan respons gagal dengan kode 422 (Unprocessable Entity)
        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['category', 'budget']);
    }
}