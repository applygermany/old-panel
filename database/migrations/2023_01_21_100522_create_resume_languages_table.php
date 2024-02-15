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
        Schema::create('resume_languages', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('resume_id');
            $table->string('title', 250)->nullable();
            $table->string('fluency_level', 3)->nullable();
            $table->string('degree', 250)->nullable();
            $table->integer('score')->nullable()->default(0);
            $table->string('current_status')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('resume_languages');
    }
};
