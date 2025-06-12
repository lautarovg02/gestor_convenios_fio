<?php
namespace App\Services;
use App\Models\CompanyEntity;

class CompanyEntityService
{
    public function getOrCreateByName(string $name): CompanyEntity
    {
        return CompanyEntity::firstOrCreate(
            ['name' => $name],
            ['name' => $name]);
    }
}
