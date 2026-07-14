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
        Schema::table('contract_rejections', function (Blueprint $table) {
            $table->dropForeign(['contract_id']);
            $table->dropColumn('contract_id');
            $table->morphs('rejectable');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contract_rejections', function (Blueprint $table) {
            $table->dropMorphs('rejectable');
            $table->unsignedBigInteger('contract_id')->after('id');
            $table->foreign('contract_id')->references('id')->on('contracts')->onDelete('cascade');
        });
    }
};
