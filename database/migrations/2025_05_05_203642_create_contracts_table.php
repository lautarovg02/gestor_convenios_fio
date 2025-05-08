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
        Schema::create('contracts', function (Blueprint $table) {
            $table->id();
            $table->date('signing_date')->nullable();
            $table->string('url_certificate_afip',200)->nullable();
            $table->string('url_statute',200)->nullable();
            $table->string('url_assignment_authorities',200)->nullable();

            $table->unsignedBigInteger('company_id');
            $table->foreign('company_id')->references('id')->on('companies');

            $table->unsignedBigInteger('secretary_id');
            $table->foreign('secretary_id')->references('id')->on('secretaries');

            $table->unsignedBigInteger('teacher_id');
            $table->foreign('teacher_id')->references('id')->on('teachers');

            $table->date('creation_date');

            $table->unsignedBigInteger('contact_employee_id');
            $table->foreign('contact_employee_id')->references('id')->on('employees');

            $table->unsignedBigInteger('representative_employee_id')->nullable();
            $table->foreign('representative_employee_id')->references('id')->on('employees');

            $table->unsignedBigInteger('rector');
            $table->foreign('rector')->references('id')->on('teachers');

            $table->unsignedBigInteger('contract_status_id');
            $table->foreign('contract_status_id')->references('id')->on('contract_statuses');

            $table->unsignedBigInteger('type_framework_agreement_id');
            $table->foreign('type_framework_agreement_id')->references('id')->on('type_framework_agreements');

            $table->binary('file')->nullable(); // Esto es un campo tipo BLOB



            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contracts');
    }
};
