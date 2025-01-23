<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class UserRegistrationTest extends TestCase
{

    use RefreshDatabase; //Secara otomatis mengosongkan database sebelum setiap pengujian
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }


    public function test_user_registration_successful()
    {
        $response = $this->postJson('/api/users/register', [
            'name' => 'John Doe',
            'email' => 'john.doe@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertStatus(201)
                 ->assertJson([
                     'message' => 'Sukses mendaftarkan pengguna.',
                 ]);

        $this->assertDatabaseHas('users', [
            'email' => 'john.doe@example.com',
        ]); //Memastikan data berhasil disimpan ke database
    }

    public function test_registration_fails_if_email_already_exists()
    {
        // Buat pengguna dengan email yang sama
        User::factory()->create([
            'email' => 'john.doe@example.com',
        ]);

        $response = $this->postJson('/api/users/register', [
            'name' => 'John Doe',
            'email' => 'john.doe@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors('email'); //Memastikan validasi input menghasilkan pesan kesalahan sesuai.
    }

    public function test_registration_fails_if_required_fields_missing()
    {
        $response = $this->postJson('/api/users/register', [
            'name' => '', // Kosong
            'email' => 'not-an-email', // Format email salah
            'password' => 'short', // Password terlalu pendek
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['name', 'email', 'password']);
    }


    public function test_registration_fails_if_password_confirmation_does_not_match()
    {
        $response = $this->postJson('/api/users/register', [
            'name' => 'John Doe',
            'email' => 'john.doe@example.com',
            'password' => 'password123',
            'password_confirmation' => 'wrongpassword',
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors('password');
    }



}