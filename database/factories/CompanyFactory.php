<?php

namespace Database\Factories;

use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Company>
 */
class CompanyFactory extends Factory
{

    protected $model = Company::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nit' => $this->generateNIT(),
            'name' => fake()->company(),
            'address' => fake()->address(),
            'phone' => fake()->phoneNumber(),
            'active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    /**
     * Generar un NIT falso con formato colombiano
     * Formato: XXXXXXXXX-X (9 dígitos + dígito de verificación)
     */
    private function generateNIT()
    {
        // Generar 9 dígitos base
        $baseNumber = $this->faker->numerify('#########');
        
        // Calcular dígito de verificación (algoritmo simplificado)
        $checkDigit = $this->calculateCheckDigit($baseNumber);
        
        return $baseNumber . '-' . $checkDigit;
    }

    /**
     * Calcular dígito de verificación para NIT
     */
    private function calculateCheckDigit($number)
    {
        $factors = [3, 7, 13, 17, 19, 23, 29, 37, 41];
        $sum = 0;
        
        for ($i = 0; $i < 9; $i++) {
            $sum += (int)$number[$i] * $factors[$i];
        }
        
        $remainder = $sum % 11;
        
        if ($remainder < 2) {
            return $remainder;
        }
        
        return 11 - $remainder;
    }
}
