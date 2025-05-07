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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('name',40);
            $table->string('last_name',40);
            $table->integer('dni')->unique();
            $table->bigInteger('cuil')->unique()->nullable();
            $table->string('email')->unique();
            $table->bigInteger('phone_numb')->unique()->length(10);
            $table->string('career',250)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
