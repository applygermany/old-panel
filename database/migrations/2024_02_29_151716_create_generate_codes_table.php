<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGenerateCodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('generate_codes', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->nullable()->comment('0 is Admin General Code');
            $table->string('generated_code')->nullable()->comment('code generated');
            $table->string('email')->nullable()->comment('Send Email To');
            $table->string('expire_time')->nullable()->comment('expire code time');
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
        Schema::dropIfExists('generate_codes');
    }
}
