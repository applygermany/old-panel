<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('uploads', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('title', 250)->nullable();
            $table->text('text')->nullable();
            $table->string('date', 250)->nullable();
            $table->tinyInteger('type')->default(100)->comment('-12:ذرومه-13:انگیزهنامه1:تمام مدارک دبیرستان-2:گواهی قبولی کنکور-3:مدرک زبان-4:پاسپورت-5:گواهی کار-6:عکس پرسنلی-7:قرارداد-8:تمامی مدارک دوره کارشناسی-9:توصیه نامه-10:گواهی شرکت دوره های تخصصی-11:گواهی شرح دروس');
            $table->integer('user_id')->nullable();
            $table->text('file_url')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('uploads');
    }
};
