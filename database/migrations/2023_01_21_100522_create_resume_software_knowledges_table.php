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
        Schema::create('resume_software_knowledges', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('resume_id');
            $table->string('title', 250)->nullable();
            $table->tinyInteger('fluency_level')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('resume_software_knowledges');
    }
};
