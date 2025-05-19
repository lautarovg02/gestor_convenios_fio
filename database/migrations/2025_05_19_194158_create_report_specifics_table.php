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
        Schema::create('report_specifics', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('specific_id');
            $table->foreign('specific_id')->references('id')->on('specifics');

            $table->unsignedBigInteger('specific_contract_id');
            $table->foreign('specific_contract_id')->references('contract_id')->on('specifics');

            $table->date('upload_date');
            $table->integer('type');
            $table->string('url_report');

            $table->unsignedBigInteger('type_report_id');
            $table->foreign('type_report_id')->references('id')->on('type__reports');

            $table->binary('file')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('report_specifics');
    }
};
