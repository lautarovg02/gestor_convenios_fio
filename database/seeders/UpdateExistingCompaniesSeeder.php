<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Company;

class UpdateExistingCompaniesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rubros = ['Industrial', 'Comercial', 'Tecnológico', 'Servicios', 'Agropecuario'];
        $dedicaciones = ['Tiempo Completo', 'Tiempo Parcial', 'Dedicación Exclusiva'];

        $companies = Company::whereNull('rubro')->orWhereNull('dedicacion')->get();

        foreach ($companies as $company) {
            $company->update([
                'rubro' => $company->rubro ?? $rubros[array_rand($rubros)],
                'dedicacion' => $company->dedicacion ?? $dedicaciones[array_rand($dedicaciones)],
            ]);
        }

        $this->command->info("Se han actualizado " . $companies->count() . " empresas con datos obligatorios por defecto.");
    }
}
