<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentScholarSpecificTable extends Migration
{
    public function up()
    {
        Schema::create('student_scholar_specific', function (Blueprint $table) {
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('specific_id');
            $table->unsignedBigInteger('specific_contract_id')->nullable();

            // Claves primarias compuestas
            $table->primary(['student_id', 'specific_id']);

            // Claves foráneas
            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
            $table->foreign('specific_id')->references('id')->on('specifics')->onDelete('cascade');
            $table->foreign('specific_contract_id')->references('id')->on('contracts')->onDelete('set null');
            

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('student_scholar_specific');
    }
}
