<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;
use App\Models\Type_Report;

class TypeReportMigrationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_creates_type_reports_table()
    {
        $this->assertTrue(Schema::hasTable('type__reports'));
    }

    /** @test */
    public function it_has_expected_columns_in_type_reports_table()
    {
        $this->assertTrue(Schema::hasColumns('type__reports', [
            'id',
            'type',
        ]));
    }

    /** @test */
    public function it_can_insert_a_valid_type_report()
    {
        $report = Type_Report::create([
            'type' => 'Informe técnico',
        ]);

        $this->assertDatabaseHas('type__reports', [
            'type' => 'Informe técnico',
        ]);
    }

    /** @test */
    public function type_field_cannot_be_null()
    {
        $this->expectException(\Illuminate\Database\QueryException::class);

        Type_Report::create([
            // 'type' => null, // omitido para provocar el error
        ]);
    }
}

