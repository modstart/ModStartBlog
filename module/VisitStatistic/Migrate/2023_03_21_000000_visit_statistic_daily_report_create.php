<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class VisitStatisticDailyReportCreate extends Migration
{
    
    public function up()
    {

        Schema::create('visit_statistic_daily_report', function (Blueprint $table) {

            $table->bigIncrements('id');
            $table->timestamps();

            $table->date('day')->nullable()->comment('');
            $table->integer('uv')->nullable()->comment('');
            $table->integer('pv')->nullable()->comment('');

            $table->unique(['day']);

        });

    }

    
    public function down()
    {
    }
}
