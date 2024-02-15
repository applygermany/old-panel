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
        Schema::create('user_universities', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('user_id');
            $table->integer('university_id');
            $table->string('field', 250)->nullable();
            $table->integer('chance_getting')->nullable();
            $table->text('description')->nullable();
            $table->integer('offer')->nullable();
            $table->tinyInteger('status')->default(3)->comment('1:in basket-2:not in basket-3:ready');
            $table->date('deadline')->nullable();
            $table->text('link')->nullable();
            $table->tinyInteger('level_status')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_universities');
    }
};
