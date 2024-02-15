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
        Schema::create('apply_levels', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('title', 250);
            $table->integer('pos');
            $table->text('text');
            $table->text('link')->nullable();
            $table->integer('phase')->default(1);
            $table->tinyInteger('phase_percent');
            $table->tinyInteger('progress_percent');
            $table->string('next_level_button', 250);
            $table->string('filename', 250)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('apply_levels');
    }
};
