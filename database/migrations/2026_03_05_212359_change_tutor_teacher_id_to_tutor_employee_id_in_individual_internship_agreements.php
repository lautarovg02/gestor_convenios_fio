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
            $table->dropForeign(['tutor_teacher_id']);
            $table->dropColumn('tutor_teacher_id');

            $table->unsignedBigInteger('tutor_employee_id')->nullable()->after('student_id');
            $table->foreign('tutor_employee_id')->references('id')->on('employees')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('individual_internship_agreements', function (Blueprint $table) {
            $table->dropForeign(['tutor_employee_id']);
            $table->dropColumn('tutor_employee_id');

            $table->unsignedBigInteger('tutor_teacher_id')->nullable()->after('student_id');
            $table->foreign('tutor_teacher_id')->references('id')->on('teachers')->nullOnDelete();
        });
    }
};
