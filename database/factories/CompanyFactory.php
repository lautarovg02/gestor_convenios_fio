<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\City;
use App\Models\CompanyEntity;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Company>
 */
class CompanyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $companyName = $this->faker->unique()->company;

        return [
            'denomination' => 'Razón social nro: ' . $this->faker->randomNumber(2),
            'cuit' => $this->faker->unique()->numberBetween(20000000000, 90999999000),
            'company_name' => $companyName,
            'entity_id' => CompanyEntity::inRandomOrder()->first()->id ?? CompanyEntity::factory()->create()->id,
            'city_id' => City::inRandomOrder()->first()->id,
            'slug' => Str::slug($companyName),
        ];
    }

    /**
     * Load unique company names from a CSV file.
     *
     * @param string $filePath
     * @return array
     */
    public static function loadCompanyNamesFromCSV(string $filePath): array
{
    if (!file_exists($filePath)) {
        throw new \Exception("File not found: $filePath");
    }

    $file = fopen($filePath, 'r');
    $names = [];

    // Leer la primera línea y eliminar el BOM si existe
    $firstLine = fgets($file);
    $firstLine = str_replace("\xEF\xBB\xBF", '', $firstLine); // Remover BOM UTF-8
    $names[] = trim($firstLine);

    // Continuar leyendo el resto del archivo
    while (($line = fgetcsv($file)) !== false) {
        $names[] = trim($line[0]); // Asume que el nombre está en la primera columna
    }

    fclose($file);

    // Eliminar el encabezado y duplicados
    return array_values(array_unique(array_filter($names, function ($name) {
        return strtolower($name) !== 'companyname'; // Eliminar encabezado
    })));
}

}
