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
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('password', 250)->nullable();
            $table->string('email', 250)->nullable();
            $table->string('mobile', 250)->nullable();
            $table->text('father_name')->nullable();
            $table->timestamp('created_at')->nullable()->useCurrent();
            $table->timestamp('updated_at')->nullable();
            $table->string('code', 250)->nullable();
            $table->integer('user_id')->default(0);
            $table->tinyInteger('status')->default(1)->comment('1:active-2:not active');
            $table->string('firstname', 250)->nullable();
            $table->string('lastname', 250)->nullable();
            $table->string('codemelli', 10)->nullable();
            $table->timestamp('birth_date')->nullable();
            $table->string('firstname_en', 250)->nullable();
            $table->string('lastname_en', 250)->nullable();
            $table->string('remember_token', 250)->nullable();
            $table->tinyInteger('verified')->default(2)->comment('1:yes-2:no');
            $table->tinyInteger('level')->default(1)->comment('1:user
-2:support پشتیبان
-3:supervisor كارشناس ارشد
-4:admin
-5:expert كارشناس
-6:writer نگارنده
-7:consultant مشاور');
            $table->tinyInteger('type')->default(1)->comment('1:normal-2:special-3-middle');
            $table->tinyInteger('max_university_count')->default(6);
            $table->integer('category_id')->default(0);
            $table->integer('charge')->default(0);
            $table->tinyInteger('darkmode')->default(0)->comment('0:no-1:yes');
            $table->string('acquainted_way', 250)->nullable();
            $table->string('admin_permissions')->nullable()->default('');
            $table->integer('isSuperAdmin')->nullable()->default(0);
            $table->bigInteger('last_login')->nullable();
            $table->integer('upload_access')->default(0);
            $table->integer('has_seen_modal')->default(-1);
            $table->json('notification_users')->nullable();
            $table->integer('wp_user_id')->default(0);
            $table->string('contract_code', 191)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
};
