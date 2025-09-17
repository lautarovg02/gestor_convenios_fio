<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('secretaries', function (Blueprint $table) {
            $table->id();

            // Relación 1:1 con users
            $table->unsignedBigInteger('user_id')->unique();
            $table->foreign('user_id')
                  ->references('id')->on('users')
                  ->cascadeOnUpdate()
                  ->cascadeOnDelete();

            // Atributos propios del secretary
            $table->string('username', 40)->unique();
            // Si querés guardar un correo secundario del perfil, dejalo nullable.
            // $table->string('email', 100)->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('secretaries');
    }
};
