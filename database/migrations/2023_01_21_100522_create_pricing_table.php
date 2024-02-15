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
        Schema::create('pricing', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('resume_price');
            $table->integer('resume_bi_price');
            $table->integer('motivation_price');
            $table->integer('invite_action');
            $table->integer('euro_price')->default(0);
            $table->integer('package_2_price');
            $table->integer('package_price')->nullable()->default(0);
            $table->integer('tel_maximum_price');
            $table->integer('extra_university_price');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pricing');
    }
};
