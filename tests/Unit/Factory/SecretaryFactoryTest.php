<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Secretary;
use Illuminate\Foundation\Testing\RefreshDatabase;


class SecretaryFactoryTest extends TestCase
{
    use RefreshDatabase;



    /**
     * Test that the factory creates a Secretary with valid attributes.
      @lautarovg02
      @test
     */
    public function test_factory_creates_valid_secretary()
    {
        $secretary = Secretary::factory()->create();

        $this->assertNotNull($secretary);
        $this->assertDatabaseHas('secretaries', [
            'user_name' => $secretary->user_name,
            'email' => $secretary->email,
        ]);
    }

    /**
     * Test that the factory generates unique emails.
      @lautarovg02
      @test
     */
    public function test_factory_generates_unique_email()
    {
        Secretary::factory()->create(['email' => 'test@example.com']);

        $this->expectException(\Illuminate\Database\QueryException::class);
        Secretary::factory()->create(['email' => 'test@example.com']);
    }

    /**
     * Test that the factory has all necessary attributes defined.
      @lautarovg02
      @test
     */
    public function test_factory_has_required_attributes()
    {
        $secretary = Secretary::factory()->make();

        $this->assertNotNull($secretary->user_name);
        $this->assertNotNull($secretary->email);
        $this->assertNotNull($secretary->password);
    }

    /**
     * Test that the factory generates realistic data.
      @lautarovg02
      @test
     */
    public function test_factory_generates_realistic_data()
    {
        $secretary = Secretary::factory()->make();

        $this->assertIsString($secretary->user_name);
        $this->assertMatchesRegularExpression('/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/', $secretary->email);
        $this->assertGreaterThanOrEqual(8, strlen($secretary->password)); // Check password length
    }
}
