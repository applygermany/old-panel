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
        Schema::create('factors', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('user_id');
            $table->tinyInteger('status')->nullable()->default(0)->comment('0:new-1:payed');
            $table->integer('amount')->nullable();
            $table->integer('amount_euro')->nullable();
            $table->integer('discount')->nullable()->default(0);
            $table->integer('off_type')->nullable()->default(1)->comment('1: percent, 2:value');
            $table->string('discount_desc', 500)->nullable();
            $table->timestamps();
            $table->integer('amount_final')->nullable()->default(0);
            $table->string('factor_desc')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('factors');
    }
};
