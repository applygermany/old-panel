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
        Schema::create('user_apply_level_status', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('user_id');
            $table->tinyInteger('phase1')->default(0);
            $table->tinyInteger('phase2')->default(0);
            $table->tinyInteger('phase3')->default(0);
            $table->tinyInteger('phase4')->default(0);
            $table->tinyInteger('phase5')->default(0);
            $table->tinyInteger('total')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_apply_level_status');
    }
};
