<?php

namespace App\Providers;

use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Contracts\Auth\Authenticatable;

class CustomUserProvider extends EloquentUserProvider
{
    public function validateCredentials(Authenticatable $user, array $credentials): bool
    {
        $passwordField = $user->getAuthPasswordName();
        $plain = $credentials['password'] ?? $credentials[$passwordField] ?? null;

        if ($plain === null) {
            return false;
        }

        return $this->hasher->check($plain, $user->getAuthPassword());
    }

    public function retrieveByCredentials(array $credentials): ?Authenticatable
    {
        $passwordField = 'password';
        $filteredCredentials = array_filter(
            $credentials,
            fn($key) => !str_contains($key, $passwordField),
            ARRAY_FILTER_USE_KEY
        );

        return parent::retrieveByCredentials($filteredCredentials);
    }
}
