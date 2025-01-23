<?php
namespace App\Services;

use App\Helpers\LoggerHelper;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UserService
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Mendaftarkan pengguna baru.
     *
     * @param array $data
     * @return mixed
     * @throws ValidationException
     */
    public function register(array $data)
    {
        LoggerHelper::logInfo('Register process started.', ['email' => $data['email']]);

        try {
            // Cek apakah email sudah digunakan
            if ($this->userRepository->findByEmail($data['email'])) {
                LoggerHelper::logWarning('Email already exists.', ['email' => $data['email']]);
                throw ValidationException::withMessages(['email' => 'Email sudah digunakan.']);
            }

            // Hash password
            $data['password'] = Hash::make($data['password']);
            LoggerHelper::logInfo('Password hashed successfully.', ['email' => $data['email']]);

            // Simpan data pengguna
            $user = $this->userRepository->create($data);
            LoggerHelper::logInfo('User registered successfully.', ['user_id' => $user->id, 'email' => $user->email]);

            return $user;
        } catch (ValidationException $e) {
            LoggerHelper::logWarning('Validation error during registration.', [
                'email'         => $data['email'],
                'error_message' => $e->getMessage(),
            ]);
            throw $e;
        } catch (\Exception $e) {
            LoggerHelper::logError('An unexpected error occurred during registration.', $e);
            throw $e;
        }
    }
}