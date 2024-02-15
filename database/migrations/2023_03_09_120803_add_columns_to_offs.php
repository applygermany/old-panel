<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToOffs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('offs', function (Blueprint $table) {
            $table->string('start_date', 10)->nullable();
            $table->enum('type', ['final', 'resume', 'tel-support', 'other'])->default('tel-support');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('offs', function (Blueprint $table) {
            //
        });
    }
}
