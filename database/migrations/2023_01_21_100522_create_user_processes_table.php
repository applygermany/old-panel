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
        Schema::create('user_processes', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('user_id');
            $table->string('last_tracking', 250)->nullable();
            $table->string('next_tracking', 250)->nullable();
            $table->text('next_step')->nullable();
            $table->text('text')->nullable();
            $table->string('progress')->nullable();
            $table->tinyInteger('contract_sent')->default(0);
            $table->tinyInteger('contract_sign')->default(0);
            $table->tinyInteger('language_degree')->default(0);
            $table->tinyInteger('translate')->default(0);
            $table->tinyInteger('embassy_approve')->default(0);
            $table->tinyInteger('document_upload')->default(0);
            $table->tinyInteger('document_check')->default(0);
            $table->tinyInteger('resume')->default(0);
            $table->tinyInteger('motivation')->default(0);
            $table->tinyInteger('university_list')->default(0);
            $table->tinyInteger('document_post')->default(0);
            $table->tinyInteger('purify')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_processes');
    }
};
