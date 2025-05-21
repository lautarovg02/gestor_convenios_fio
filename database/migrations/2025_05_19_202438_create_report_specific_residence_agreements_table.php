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
        Schema::create('report_specific_residence_agreements', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('specific_residence_agreement_id');
            $table->foreign('specific_residence_agreement_id','fk_res_agreement')->references('id')->on('specific_residence_agreements');

            $table->unsignedBigInteger('specific_residence_agreement_contract_id');
            $table->foreign('specific_residence_agreement_contract_id', 'fk_res_contract_agreement')->references('contract_id')->on('specific_residence_agreements');

            $table->date('upload_date');
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
        Schema::dropIfExists('report_specific_residence_agreements');
    }
};
