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
            $table->renameColumn('rejectable_id', 'agreement_id');
            $table->renameColumn('rejectable_type', 'agreement_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contract_rejections', function (Blueprint $table) {
            $table->renameColumn('agreement_id', 'rejectable_id');
            $table->renameColumn('agreement_type', 'rejectable_type');
        });
    }
};
