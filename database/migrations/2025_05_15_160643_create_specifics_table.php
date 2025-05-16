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
        Schema::create('specifics', function (Blueprint $table) {
        $table->unsignedBigInteger('contract_id');
        $table->foreign('contract_id')->references('id')->on('contracts');

        $table->date('signing_date')->nullable();
        $table->string('objective', 200);
        $table->text('commitment_parties');
        $table->string('responsable_control_company', 100)->nullable();
        $table->string('responsable_control_fio', 100)->nullable();
        $table->binary('file')->nullable();

        $table->timestamps();

    });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('specifics');
    }
};
