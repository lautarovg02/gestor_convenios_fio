<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('teachers', function (Blueprint $table) {
            $table->id();

            // Relación 1:1 con users
            $table->unsignedBigInteger('user_id')->unique();
            $table->foreign('user_id')
                  ->references('id')->on('users')
                  ->cascadeOnUpdate()
                  ->cascadeOnDelete();

            // Atributos propios del teacher
            $table->string('name', 40);
            $table->string('lastname', 40);
            $table->unsignedBigInteger('dni');
            $table->unsignedBigInteger('cuil')->nullable();
            $table->string('faculty', 20)->nullable();
            $table->boolean('is_rector')->default(false);
            $table->boolean('is_dean')->default(false);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('teachers');
    }
};
