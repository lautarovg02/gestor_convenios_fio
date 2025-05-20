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
        Schema::create('report_individual_internship_agreements', function (Blueprint $table) {
            $table->id();
            
            $table->unsignedBigInteger('individual_internship_id');
            $table->foreign('individual_internship_id', 'fk_rpt_indiv_internship')->references('id')->on('individual_internship_agreements');
            
            $table->unsignedBigInteger('individual_internship_contract_id');
            $table->foreign('individual_internship_contract_id', 'fk_indiv_intern_contract')->references('contract_id')->on('individual_internship_agreements');
            
            $table->date('upload_date');
            $table->string('url_report');

            $table->unsignedBigInteger('type_report_id');
            $table->foreign('type_report_id')->references('id')->on('type__reports');
           
            $table->binary('file')->nullable();
            $table->timestamps();

            
            
            
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('report_individual_internship_agreements');
    }
};
