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
        Schema::create('webinars', function (Blueprint $table) {
            $table->integer('id', true);
            $table->text('title')->nullable();
            $table->text('time');
            $table->text('headlines');
            $table->text('payment_text');
            $table->text('payment_link')->nullable();
            $table->integer('price')->nullable()->default(0);
            $table->string('organizer_name', 250);
            $table->string('organizer_field', 250);
            $table->text('first_meeting');
            $table->string('first_meeting_start_time', 250);
            $table->string('first_meeting_end_time', 250);
            $table->text('second_meeting');
            $table->string('second_meeting_start_time', 250);
            $table->string('second_meeting_end_time', 250);
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
        Schema::dropIfExists('webinars');
    }
};
