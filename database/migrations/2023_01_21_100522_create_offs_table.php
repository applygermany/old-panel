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
        Schema::create('offs', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('user_id')->default(0);
            $table->integer('discount');
            $table->string('code');
            $table->tinyInteger('discount_type')->comment('1:percent-2:value');
            $table->string('end_date', 10)->nullable();
            $table->tinyInteger('status')->default(1)->comment('1:active-2:deactive');
            $table->integer('maximum_usage')->default(0);
            $table->integer('current_usage')->default(0);
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
        Schema::dropIfExists('offs');
    }
};
