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
        Schema::create('resume_works', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('resume_id');
            $table->string('from_date_year', 20)->nullable();
            $table->string('from_date_month', 20)->nullable();
            $table->string('to_date_year', 20)->nullable();
            $table->string('to_date_month', 20)->nullable();
            $table->string('company_name', 250)->nullable();
            $table->string('position', 250)->nullable();
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
        Schema::dropIfExists('resume_works');
    }
};
