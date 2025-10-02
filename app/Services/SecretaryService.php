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
                'username' => $data['user_secretaria'],
            ],
            [
                'username' => 'user_' . Str::random(8),
            ]
        );
    }
}
