<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class VisitStatisticItemCreate extends Migration
{
    
    public function up()
    {

        Schema::create('visit_statistic_item', function (Blueprint $table) {

            $table->bigIncrements('id');
            $table->timestamps();

            $table->string('url', 200)->nullable()->comment('');
            $table->string('ip', 16)->nullable()->comment('');
            
            $table->tinyInteger('device')->nullable()->comment('');
            $table->string('ua', 200)->nullable()->comment('');

            $table->index(['created_at']);

        });

    }

    
    public function down()
    {
    }
}
