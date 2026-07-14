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
        Schema::table('specifics', function (Blueprint $table) {
            $table->unsignedBigInteger('contract_status_id')->nullable()->after('contract_id');
            $table->foreign('contract_status_id')->references('id')->on('contract_statuses');
        });

        Schema::table('specific_residence_agreements', function (Blueprint $table) {
            $table->unsignedBigInteger('contract_status_id')->nullable()->after('contract_id');
            $table->foreign('contract_status_id')->references('id')->on('contract_statuses');
        });

        Schema::table('individual_internship_agreements', function (Blueprint $table) {
            $table->unsignedBigInteger('contract_status_id')->nullable()->after('contract_id');
            $table->foreign('contract_status_id')->references('id')->on('contract_statuses');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('specifics', function (Blueprint $table) {
            $table->dropForeign(['contract_status_id']);
            $table->dropColumn('contract_status_id');
        });

        Schema::table('specific_residence_agreements', function (Blueprint $table) {
            $table->dropForeign(['contract_status_id']);
            $table->dropColumn('contract_status_id');
        });

        Schema::table('individual_internship_agreements', function (Blueprint $table) {
            $table->dropForeign(['contract_status_id']);
            $table->dropColumn('contract_status_id');
        });
    }
};
