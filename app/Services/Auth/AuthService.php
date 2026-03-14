<?php

namespace App\Services\Auth;

use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use RuntimeException;

class AuthService
{
    public function __construct(private readonly JwtService $jwtService)
    {
    }

    public function register(array $data): array
    {
        $role = Role::query()->where('name', $data['role'])->first();
        if (!$role) {
            throw new RuntimeException('Role is invalid.');
        }

        $user = User::query()->create([
            'role_id' => $role->id,
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'password' => Hash::make($data['password']),
            'is_active' => true,
        ]);

        return $this->buildAuthPayload($user);
    }

    public function login(array $credentials): array
    {
        $user = User::query()->with('role')->where('email', $credentials['email'])->first();
        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            throw new RuntimeException('Email or password is invalid.');
        }

        if (!$user->is_active) {
            throw new RuntimeException('User is disabled.');
        }

        return $this->buildAuthPayload($user);
    }

    private function buildAuthPayload(User $user): array
    {
        $token = $this->jwtService->issueToken([
            'sub' => $user->id,
            'email' => $user->email,
            'role' => optional($user->role)->name,
        ]);

        return [
            'access_token' => $token,
            'token_type' => 'Bearer',
            'expires_in' => 3600,
            'user' => $user->load('role'),
        ];
    }
}
