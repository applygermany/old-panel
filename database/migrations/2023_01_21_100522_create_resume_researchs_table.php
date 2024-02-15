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
        Schema::create('resume_researchs', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('resume_id');
            $table->string('type', 250)->nullable();
            $table->string('title', 250)->nullable();
            $table->string('year', 4)->nullable();
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
        Schema::dropIfExists('resume_researchs');
    }
};
