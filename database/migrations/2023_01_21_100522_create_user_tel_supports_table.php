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
        Schema::create('user_tel_supports', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('tel_support_id');
            $table->integer('supervisor_id');
            $table->integer('user_id');
            $table->string('title', 250)->nullable();
            $table->date('tel_date');
            $table->text('advise')->nullable();
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
        Schema::dropIfExists('user_tel_supports');
    }
};
