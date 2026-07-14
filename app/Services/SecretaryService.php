<?php

namespace App\Services;

use App\Models\Secretary;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Collection;

class SecretaryService
{
    /**
     * Crea o devuelve una secretaria con datos aleatorios de login.
     *
     * @param array $data
     * @return Secretary
     */


    public function getAllSecretaries(): Collection
    {
        return Secretary::all();
    }
}
