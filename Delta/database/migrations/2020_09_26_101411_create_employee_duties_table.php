<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Query\Expression;

class CreateEmployeeDutiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_duties', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id');
            $table->unsignedBigInteger('duty_status_id')->default(3);
            $table->dateTime('enter_time')->nullable();
            $table->dateTime('exit_time')->nullable();
            $table->time('fixed_duty_hour')->nullable();
            $table->time('worked_hour')->nullable();
            $table->integer('daily_salary')->default(0);
            $table->integer('daily_salary_extra')->default(0);
            $table->date('date');
            $table->longText('comment')->nullable();
            $table->json('data')->default(json_encode(['']));
            $table->softDeletes();
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
        Schema::dropIfExists('employee_duties');
    }
}
