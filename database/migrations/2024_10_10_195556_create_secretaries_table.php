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
            $table->unsignedBigInteger('user_id')->unique()->nullable();
            
            $table->foreign('user_id')
                ->references('id')->on('users')
                ->cascadeOnUpdate()
                ->nullOnDelete();

            // ELIMINAMOS 'username'. No es necesario.
            // Si necesitas datos extra, agrega teléfono o legajo, pero el usuario ya está en 'users'

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('secretaries');
    }
};