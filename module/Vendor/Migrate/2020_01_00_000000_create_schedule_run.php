<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use ModStart\Core\Dao\ModelManageUtil;

class CreateScheduleRun extends Migration
{
    
    public function up()
    {
        Schema::create('schedule_run', function (Blueprint $table) {

            $table->bigIncrements('id');
            $table->timestamps();

            $table->string('name', 200)->nullable()->comment('');
            $table->dateTime('startTime')->nullable()->comment('');
            $table->dateTime('endTime')->nullable()->comment('');
            
            $table->tinyInteger('status')->nullable()->comment('');
            $table->string('result', 200)->nullable()->comment('');

            $table->index('created_at');
        });
    }

    
    public function down()
    {

    }
}
