<?php

namespace Tests\Feature;

use App\Models\Reminder;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReminderTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_reminder()
    {
        $user = User::factory()->create();
         // Login untuk mendapatkan token JWT
        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'password', // Pastikan sesuai dengan factory User
        ]);

        $token = $response->json('token');


        $data = [
            'user_id' => $user->id,
            'title' => 'Electricity Bill',
            'amount' => 750000,
            'due_date' => '2025-02-01',
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/reminders', $data);

        $response->assertStatus(201)
            ->assertJson([
                'message' => 'Reminder added successfully.',
                'data' => [
                    'title' => 'Electricity Bill',
                    'amount' => 750000,
                    'due_date' => '2025-02-01',
                ],
            ]);

        $this->assertDatabaseHas('reminders', $data);
    }
}