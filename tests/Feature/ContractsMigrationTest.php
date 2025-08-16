<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class ContractsMigrationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function contracts_table_has_fecha_fin_column()
    {
        $this->assertTrue(
            Schema::hasColumn('contracts', 'fecha_fin'),
            'La tabla contracts no tiene la columna fecha_fin'
        );
    }
       /** @test */
    public function contracts_table_has_fecha_fin_column_of_type_date()
    {
        $column = DB::getDoctrineColumn('contracts', 'fecha_fin');
        $this->assertEquals('date', $column->getType()->getName(), 'fecha_fin no es de tipo date');
    }

    /** @test */
    public function contracts_table_has_blob_columns()
    {
       $columns = ['url_certificate_afip', 'url_statute', 'url_assignment_authorities'];


        foreach ($columns as $column) {
            $this->assertTrue(
                Schema::hasColumn('contracts', $column),
                "La tabla contracts no tiene la columna {$column}"
            );
        }
    }

      /** @test */
    public function contracts_table_blob_columns_are_binary()
    {
        $columns = ['url_certificate_afip', 'url_statute', 'url_assignment_authorities'];

        foreach ($columns as $columnName) {
            $column = DB::getDoctrineColumn('contracts', $columnName);
            $this->assertEquals('blob', $column->getType()->getName(), "La columna {$columnName} no es BLOB");
        }
    }

   
}
