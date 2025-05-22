<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Company;
use Database\Factories\CompanyFactory;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CompanyControllerTest extends TestCase
{
    use DatabaseTransactions, WithFaker;

    /**
     * Test para obtener una empresa específica (GET /companies/{id})
     */
    public function test_can_get_all_companies()
    {
        $companies = Company::factory()->count(3)->create();

        $response = $this->getJson('/api/v1/companies');

        $response->assertStatus(200)
            ->assertJsonCount(3, 'data')
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'nit',
                        'name',
                        'address',
                        'phone',
                        'active',
                        'created_at',
                        'updated_at',
                        'deleted_at'
                    ]
                ]
            ]);
    }

    /**
     * Test para obtener una empresa específica (GET /companies/{nit})
     */
    public function test_can_get_company_by_nit()
    {
        $company = Company::factory()->create();

        $response = $this->getJson("/api/v1/companies/by-nit/{$company->nit}");

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'data' => [
                        'nit',
                        'name',
                        'address',
                        'phone',
                        'active',
                        'created_at',
                        'updated_at',
                        'deleted_at',
                    ]
                ]);
    }


    /**
     * Test para crear una nueva empresa (POST /companies)
     */
    public function test_can_create_company()
    {
        // Arrange
        $companyData = Company::factory()->make()->toArray();
        
        unset($companyData['id'], $companyData['created_at'], $companyData['updated_at']);

        $response = $this->postJson('/api/v1/companies', $companyData);

        $response->assertStatus(201)
                ->assertJsonStructure([
                    'data' => [
                        'nit',
                        'name',
                        'address',
                        'phone',
                        'active',
                        'created_at',
                        'updated_at',
                        'deleted_at'
                    ]
                ]);

        // Verificar que se guardó en la base de datos
        $this->assertDatabaseHas('companies', [
            'nit' => $companyData['nit'],
            'name' => $companyData['name'],
            'address' => $companyData['address'],
            'phone' => $companyData['phone'],
            'active' => $companyData['active'],
        ]);
    }


    /**
     * Test para actualizar una empresa existente (PUT /companies/{id})
     */
    public function test_can_update_company()
    {
        $company = Company::factory()->create();

        $updatedData = [
            'name' => fake()->company(),
            'address' => fake()->address(),
            'phone' => fake()->phoneNumber(),
            'active' => false,
        ];

        $response = $this->putJson("/api/v1/companies/{$company->id}", $updatedData);

        $response->assertStatus(200)
                ->assertJson([
                    'data' => [
                        'nit' => $company->nit,
                        'name' => $updatedData['name'],
                        'address' => $updatedData['address'],
                        'phone' => $updatedData['phone'],
                        'active' => $updatedData['active'],
                    ]
                ]);

        $this->assertDatabaseHas('companies', [
            'nit' => $company->nit,
            'name' => $updatedData['name'],
            'address' => $updatedData['address'],
            'phone' => $updatedData['phone'],
            'active' => $updatedData['active'],
        ]);
    }

    /**
     * Test para eliminar una empresa (DELETE /companies/{id})
     */
    public function test_can_delete_company()
    {
        $company = Company::factory()->state([
            'active' => false
        ])->create();

        $response = $this->deleteJson("/api/v1/companies/{$company->id}");

        $response->assertStatus(200)
                ->assertJson([
                    'message' => 'Empresa eliminada exitosamente'
                ]);
    }


    /**
     * Test para verificar error 401 de eliminar empresa cuando esta activa
     */
    public function test_delete_validation_active_company()
    {
        $company = Company::factory()->create();

        $response = $this->deleteJson("/api/v1/companies/{$company->id}");

        $response->assertStatus(401)
                ->assertJson([
                    'message' => 'No puede eliminar esta empresa si esta activa'
                ]);
    }

    /**
     * Test para verificar validación al crear empresa sin datos requeridos
     */
    public function test_create_company_validation_fails()
    {
        $invalidData = [
            'nit' => 'abcdefg-a', // Nit en formato incorrecto
            'name' => '', // nombre inválido
            'address' => '', // direccion inválido
            'phone' => '', // phone inválido
        ];

        $response = $this->postJson('/api/v1/companies', $invalidData);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['nit', 'name', 'address', 'phone']);
    }

}
