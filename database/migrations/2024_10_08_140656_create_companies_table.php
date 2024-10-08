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
            $table->string('denomination',40);
            $table->integer('cuit');
            $table->string('company_name',100)->nullable();
            $table->string('sector',40)->nullable();
            $table->string('entity',40)->nullable();
            $table->string('company_category',20)->nullable();
            $table->string('scope',40)->nullable();
            $table->string('street',40)->nullable();
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
