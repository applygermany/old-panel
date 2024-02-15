<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDiscountInvitersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('discount_inviters', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->default(0);
            $table->integer('discount');
            $table->string('code');
            $table->enum('currency', ['euro', 'rial'])->default('euro');
            $table->enum('discount_type', ['percent', 'fixed'])->default('fixed');
            $table->string('start_date', 10)->nullable();
            $table->string('end_date', 10)->nullable();
            $table->enum('status', ['active', 'deactive'])->default('active');
            $table->integer('maximum_usage')->default(0);
            $table->integer('current_usage')->default(0);
            $table->enum('type', ['final', 'tel-support', 'resume', 'other'])->default('final');
            $table->integer('user_usage')->default(1);
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
        Schema::dropIfExists('discount_inviters');
    }
}
