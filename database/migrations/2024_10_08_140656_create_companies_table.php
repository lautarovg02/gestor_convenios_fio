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
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('denomination');
            $table->integer('cuit')->unique();
            $table->string('company_name',100)->unique()->nullable();
            $table->string('sector')->nullable();
            $table->string('entity')->nullable();
            $table->string('company_category')->nullable();
            $table->string('scope')->nullable();
            $table->string('street')->nullable();
            $table->integer('number')->nullable();
            $table->unsignedBigInteger('city_id');
            $table->foreign('city_id')->references('id')->on('cities');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
