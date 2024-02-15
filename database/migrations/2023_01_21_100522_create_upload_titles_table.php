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
        Schema::create('upload_titles', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('title', 250);
            $table->string('format', 10);
            $table->integer('required')->comment('0 no, 1 yes');
            $table->integer('type')->comment('1:تمام مدارک دبیرستان-2:گواهی قبولی کنکور-3:مدرک زبان-4:پاسپورت-5:گواهی کار-6:عکس پرسنلی-7:قرارداد-8:تمامی مدارک دوره کارشناسی-9:توصیه نامه-10:گواهی شرکت دوره های تخصصی-11:گواهی شرح دروس');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('upload_titles');
    }
};
