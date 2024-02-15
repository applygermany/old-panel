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
        Schema::create('user_duties', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('user_id');
            $table->string('title');
            $table->string('text')->nullable();
            $table->tinyInteger('status')->default(1)->comment('1:default-2:undone-3:done');
            $table->date('deadline');
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
        Schema::dropIfExists('user_duties');
    }
};
