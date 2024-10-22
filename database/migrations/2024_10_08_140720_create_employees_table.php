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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string("name",40);
            $table->string("lastname",40);
            $table->integer("dni")->length(8)->unique();
            $table->bigInteger("cuil")->length(11)->nullable()->unique();
            $table->string("email")->nullable()->unique();
            $table->string("position");
            $table->boolean("is_represent");
            $table->unsignedBigInteger("company_id");
            $table->foreign('company_id')->references('id')->on('companies');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
