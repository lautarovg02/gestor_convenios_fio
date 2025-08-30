<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
         Schema::table('companies', function (Blueprint $table) {
       
            $table->string('url_certificate_afip')->nullable()->after('slug');
            $table->string('url_statute')->nullable()->after('url_certificate_afip');
            $table->string('url_assignment_authorities')->nullable()->after('url_statute');
            $table->string('url_confidentiality_clause_file')->nullable()->after('url_assignment_authorities');

            // Cláusula de confidencialidad
            $table->boolean('has_confidentiality_clause')->default(false)->after('url_confidentiality_clause_file');
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
       Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn([
                'afip_certificate',
                'statute_confirmation',
                'authorities_assignment',
                'has_confidentiality_clause',
                'confidentiality_clause_file',
            ]);
        });
    }
};
