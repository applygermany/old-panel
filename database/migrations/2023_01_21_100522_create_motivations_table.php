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
        Schema::create('motivations', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('user_id');
            $table->integer('price')->default(0);
            $table->tinyInteger('to')->default(1)->comment('1:embassy-2:university');
            $table->tinyInteger('country')->default(1)->comment('1:iran-2:others');
            $table->string('name', 250)->nullable();
            $table->string('family', 250)->nullable();
            $table->string('phone', 250)->nullable();
            $table->string('birth_date', 10)->nullable();
            $table->string('birth_place', 250)->nullable();
            $table->string('email', 250)->nullable();
            $table->string('address', 250)->nullable();
            $table->text('about')->nullable();
            $table->text('resume')->nullable();
            $table->text('why_germany')->nullable();
            $table->text('after_graduation')->nullable();
            $table->text('extra_text')->nullable();
            $table->tinyInteger('status')->default(1)->comment('-1 draft, 0 pre pay, 1:new-2:done-3:admin edit-4:user edit');
            $table->text('admin_message')->nullable();
            $table->text('edit_request')->nullable();
            $table->timestamps();
            $table->json('url_uploaded_from_user')->nullable();
            $table->json('url_uploaded_from_admin')->nullable();
            $table->mediumText('admin_comment')->nullable();
            $table->mediumText('user_comment')->nullable();
            $table->json('admin_attachment')->nullable();
            $table->integer('writer_id')->nullable();
            $table->json('url_uploaded_from_writer')->nullable();
            $table->string('admin_accepted_filename', 191)->nullable();
            $table->boolean('is_accepted')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('motivations');
    }
};
