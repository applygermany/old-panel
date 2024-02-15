<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('resume_education_records', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('resume_id');
            $table->string('grade', 250)->nullable();
            $table->string('from_date_year', 20)->nullable();
            $table->string('from_date_month', 20)->nullable();
            $table->string('to_date_year', 20)->nullable();
            $table->string('to_date_month', 20)->nullable();
            $table->string('school_name', 250)->nullable();
            $table->string('field', 250)->nullable();
            $table->string('grade_score', 20)->nullable();
            $table->string('city', 250)->nullable();
            $table->text('text')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('resume_education_records');
    }
};
