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
        Schema::create('individual_internship_agreements', function (Blueprint $table) {
            $table->id();
            $table->integer('months_quantity');
            $table->text('task');
            $table->date('internship_initial_date');
            $table->string('assignment',100);
            $table->string('area',100);
            $table->date('signing_date')->nullable();

            $table->unsignedBigInteger('contract_id');
            $table->foreign('contract_id')->references('id')->on('contracts');

            $table->unsignedBigInteger('student_id');
            $table->foreign('student_id')->references('id')->on('students');

            $table->binary('file')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('individual_internship_agreements');
    }
};
