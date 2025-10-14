<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Agregamos la columna
            $table->unsignedBigInteger('role_id')->after('password');

            // FK a roles
            $table->foreign('role_id')
                  ->references('id')->on('roles')
                  ->cascadeOnUpdate()
                  ->restrictOnDelete(); // evita borrar un rol en uso
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['role_id']);
            $table->dropColumn('role_id');
        });
    }
};
