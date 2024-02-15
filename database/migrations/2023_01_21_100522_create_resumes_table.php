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
        Schema::create('resumes', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('user_id');
            $table->string('theme', 250)->nullable()->default('قالب 1');
            $table->string('language', 250)->nullable();
            $table->string('name', 250)->nullable();
            $table->string('family', 250)->nullable();
            $table->date('birth_date')->nullable();
            $table->string('birth_place', 250)->nullable();
            $table->string('phone', 250)->nullable();
            $table->string('email', 250)->nullable();
            $table->text('address')->nullable();
            $table->text('socialmedia_links')->nullable();
            $table->text('text')->nullable();
            $table->tinyInteger('status')->default(1)->comment('-1:draft- 0:not paid - 1:new-2:done-3:admin edit-4:user edit 5:support accept 6 support decline');
            $table->timestamps();
            $table->string('url_uploaded_from_user')->nullable()->default('');
            $table->string('url_uploaded_from_admin')->nullable()->default('');
            $table->mediumText('admin_comment')->nullable();
            $table->mediumText('user_comment')->nullable();
            $table->string('admin_attachment', 300)->nullable();
            $table->text('edit_request')->nullable();
            $table->string('color', 7)->nullable();
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
        Schema::dropIfExists('resumes');
    }
};
