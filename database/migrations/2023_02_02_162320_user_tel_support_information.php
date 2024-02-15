<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UserTelSupportInformation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_tel_support_informations', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('user_id')->nullable();
            $table->integer('user_tel_support')->nullable();
            $table->integer('tel_support')->nullable();
            $table->text('title')->nullable();
            $table->string('military')->nullable();
            $table->string('mobile')->nullable();
            $table->string('email')->nullable();
            $table->string('birthDate')->nullable();
            $table->string('language')->nullable();
            $table->string('languageDocument')->nullable();
            $table->string('grade')->nullable();
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
        Schema::dropIfExists('user_tel_support_informations');
    }
}
