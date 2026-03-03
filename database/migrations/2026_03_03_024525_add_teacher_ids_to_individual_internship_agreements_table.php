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
        Schema::table('individual_internship_agreements', function (Blueprint $table) {
            $table->unsignedBigInteger('tutor_teacher_id')->nullable()->after('student_id');
            $table->foreign('tutor_teacher_id')->references('id')->on('teachers')->nullOnDelete();

            $table->unsignedBigInteger('docente_teacher_id')->nullable()->after('tutor_teacher_id');
            $table->foreign('docente_teacher_id')->references('id')->on('teachers')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('individual_internship_agreements', function (Blueprint $table) {
            $table->dropForeign(['tutor_teacher_id']);
            $table->dropForeign(['docente_teacher_id']);
            $table->dropColumn(['tutor_teacher_id', 'docente_teacher_id']);
        });
    }
};
