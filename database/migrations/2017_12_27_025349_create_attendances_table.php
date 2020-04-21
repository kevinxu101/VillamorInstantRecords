<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAttendancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('student_id')->unsigned();
            $table->integer('section_id')->unsigned();
            $table->integer('exam_id')->unsigned();
            $table->tinyInteger('present')->unsigned();
            $table->integer('user_id')->unsigned();

            //custom attendace
            $table->integer('jun_numOfSchoolDays')->unsigned();
            $table->integer('jul_numOfSchoolDays')->unsigned();
            $table->integer('aug_numOfSchoolDays')->unsigned();
            $table->integer('sept_numOfSchoolDays')->unsigned();
            $table->integer('occ_numOfSchoolDays')->unsigned();
            $table->integer('nov_numOfSchoolDays')->unsigned();
            $table->integer('dec_numOfSchoolDays')->unsigned();
            $table->integer('jan_numOfSchoolDays')->unsigned();
            $table->integer('feb_numOfSchoolDays')->unsigned();
            $table->integer('mar_numOfSchoolDays')->unsigned();
            $table->integer('apr_numOfSchoolDays')->unsigned();
            $table->integer('total_numOfSchoolDays')->unsigned();

            $table->integer('jun_numofDaysPresent')->unsigned();
            $table->integer('jul_numofDaysPresent')->unsigned();
            $table->integer('aug_numofDaysPresent')->unsigned();
            $table->integer('sept_numofDaysPresent')->unsigned();
            $table->integer('occ_numofDaysPresent')->unsigned();
            $table->integer('nov_numofDaysPresent')->unsigned();
            $table->integer('dec_numofDaysPresent')->unsigned();
            $table->integer('jan_numofDaysPresent')->unsigned();
            $table->integer('feb_numofDaysPresent')->unsigned();
            $table->integer('mar_numofDaysPresent')->unsigned();
            $table->integer('apr_numofDaysPresent')->unsigned();
            $table->integer('total_numofDaysPresent')->unsigned();

            $table->integer('jun_numofDaysAbsent')->unsigned();
            $table->integer('jul_numofDaysAbsent')->unsigned();
            $table->integer('aug_numofDaysAbsent')->unsigned();
            $table->integer('sept_numofDaysAbsent')->unsigned();
            $table->integer('occ_numofDaysAbsent')->unsigned();
            $table->integer('nov_numofDaysAbsent')->unsigned();
            $table->integer('dec_numofDaysAbsent')->unsigned();
            $table->integer('jan_numofDaysAbsent')->unsigned();
            $table->integer('feb_numofDaysAbsent')->unsigned();
            $table->integer('mar_numofDaysAbsent')->unsigned();
            $table->integer('apr_numofDaysAbsent')->unsigned();


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
        Schema::dropIfExists('attendances');
    }
}
