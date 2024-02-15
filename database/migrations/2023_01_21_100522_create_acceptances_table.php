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
        Schema::create('acceptances', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('user_id');
            $table->string('firstname', 250)->nullable();
            $table->string('lastname', 250)->nullable();
            $table->string('phone', 250)->nullable();
            $table->string('link', 64)->nullable();
            $table->string('birth_date', 64)->nullable();
            $table->string('mellicode', 250)->nullable();
            $table->text('fatherName')->nullable();
            $table->string('email')->nullable();
            $table->string('city', 128)->nullable();
            $table->text('address')->nullable();
            $table->string('embassy_appointment', 64)->nullable();
            $table->string('embassy_date', 128)->nullable();
            $table->string('admittance', 128)->nullable();
            $table->string('diploma_grade_average', 64)->nullable();
            $table->string('pre_university_grade_average', 64)->nullable();
            $table->string('pre_university_field', 250)->nullable();
            $table->string('field_grade', 180)->nullable();
            $table->string('is_entrance_exam', 64)->nullable();
            $table->string('is_license_semesters', 64)->nullable();
            $table->string('field_license', 180)->nullable();
            $table->string('university_license', 180)->nullable();
            $table->string('license_graduated', 64)->nullable();
            $table->string('average_license', 64)->nullable();
            $table->string('year_license', 64)->nullable();
            $table->string('total_number_passes', 64)->nullable();
            $table->string('average_now_license', 250)->nullable();
            $table->string('predicted_year_license', 250)->nullable();
            $table->string('Pass_30_units', 64)->nullable();
            $table->string('license_type', 250)->nullable();
            $table->string('associate_diploma_field', 250)->nullable();
            $table->string('associate_diploma_grade_average', 250)->nullable();
            $table->string('associate_diploma_university', 250)->nullable();
            $table->string('year_associate_diploma', 250)->nullable();
            $table->string('master_graduated', 250)->nullable();
            $table->string('senior_educate', 64)->nullable();
            $table->string('field_senior', 180)->nullable();
            $table->string('university_senior', 180)->nullable();
            $table->string('average_senior', 64)->nullable();
            $table->string('year_senior', 64)->nullable();
            $table->string('another_educate', 256)->nullable();
            $table->string('master_total_number_passes', 250)->nullable();
            $table->string('average_now_master', 250)->nullable();
            $table->string('predicted_year_master', 250)->nullable();
            $table->string('military_service', 64)->nullable();
            $table->string('doc_translate', 64)->nullable();
            $table->string('doc_translate_year_passed', 250)->nullable();
            $table->string('language_favor', 64)->nullable();
            $table->string('license_language', 64)->nullable();
            $table->string('what_intent_grade_language', 180)->nullable();
            $table->string('date_intent_grade_language', 250)->nullable();
            $table->string('date_get_grade_language', 180)->nullable();
            $table->string('score_grade_language', 65)->nullable();
            $table->string('what_grade_language', 180)->nullable();
            $table->string('current_language_status')->nullable();
            $table->string('doc_embassy', 128)->nullable();
            $table->text('description')->nullable();
            $table->text('tab_status')->nullable();
            $table->tinyInteger('last_form_submit')->default(1);
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
        Schema::dropIfExists('acceptances');
    }
};
