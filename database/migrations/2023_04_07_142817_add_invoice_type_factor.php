<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddInvoiceTypeFactor extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('factors', function (Blueprint $table) {
            $table->enum('invoice_type', ['invoice', 'receipt'])->default('receipt');
            $table->enum('payment_type', ['motivation', 'resume', 'telSupport', 'package', 'webinar', 'other'])->default('package');
            $table->enum('payment_method', ['online', 'cash', 'bank', 'none'])->default('online');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('factors', function (Blueprint $table) {
            //
        });
    }
}
