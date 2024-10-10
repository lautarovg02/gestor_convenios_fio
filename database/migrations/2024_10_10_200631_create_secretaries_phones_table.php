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
        Schema::create('secretaries_phones', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('phone_number')->length(10);
            $table->unsignedBigInteger('secretary_id');
            $table->foreign('secretary_id')->references('id')->on('secretaries');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('secretaries_phones');
    }
};
