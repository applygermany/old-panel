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
        Schema::create('resume_courses', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('resume_id');
            $table->string('title', 250)->nullable();
            $table->string('organizer', 250)->nullable();
            $table->string('year', 4)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('resume_courses');
    }
};
