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
        Schema::create('tel_supports', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('user_id');
            $table->date('day_tel');
            $table->text('day_tel_fa')->nullable();
            $table->string('from_time', 250);
            $table->string('to_time', 250);
            $table->integer('price')->nullable();
            $table->tinyInteger('status')->default(1)->comment('1:empty-2:reserve');
            $table->tinyInteger('type')->default(1)->comment('1:normal-2:special	');
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
        Schema::dropIfExists('tel_supports');
    }
};
