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
        Schema::create('user_webinars', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('webinar_id')->default(0);
            $table->string('name', 250)->nullable();
            $table->string('family', 250)->nullable();
            $table->bigInteger('price')->nullable();
            $table->string('email', 250)->nullable();
            $table->string('mobile', 250)->nullable();
            $table->string('field', 250)->nullable();
            $table->string('grade', 250)->nullable();
            $table->string('instagram', 250)->nullable();
            $table->string('telegram', 250)->nullable();
            $table->timestamps();
            $table->integer('payed')->default(1);
            $table->integer('status')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_webinars');
    }
};
