<?php

namespace App\Services;

use App\Models\Secretary;
use Illuminate\Support\Str;

class SecretaryService
{
    /**
     * Crea o devuelve una secretaria con datos aleatorios de login.
     *
     * @param array $data
     * @return Secretary
     */
    public function getOrCreateSecretary(array $data): Secretary
    {
        return Secretary::firstOrCreate(
            [
                'user_name' => $data['user_secretaria'],
                'password' => $data['password_secretaria'],
                'email' => $data['email_secretaria'],
            ],
            [
                'user_name' => 'user_' . Str::random(8),
                'password' => bcrypt(Str::random(12)),
                'email' => Str::random(10) . '@example.com',
            ]
        );
    }
}
