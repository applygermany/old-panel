<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->integer('user_id');
            $table->string('bank_account_id')->nullable();
            $table->enum('invoice_type', ['resume', 'final', 'other', 'tel-support'])->default('resume');
            $table->enum('payment_method', ['online', 'cash', 'bank'])->default('bank');
            $table->enum('payment_status', ['paid', 'unpaid'])->default('unpaid');
            $table->enum('status', ['published', 'drafted'])->default('published');
            $table->enum('invoice_title', ['pre-invoice', 'receipt'])->default('pre-invoice');
            $table->string('ir_amount')->default(0);
            $table->string('euro_amount')->default(0);
            $table->enum('discount_type', ['percent', 'fixed'])->default('percent');
            $table->string('discount_amount')->default(0);
            $table->timestamp('payment_at')->nullable();
            $table->text('invoice_description')->nullable();
            $table->text('discount_description')->nullable();
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
        Schema::dropIfExists('invoices');
    }
}
