<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyDataTempPathLength extends Migration
{
    
    public function up()
    {
        Schema::table('data_temp', function (Blueprint $table) {

            $table->string('path', 100)->nullable()->comment('')->change();

        });
    }

    
    public function down()
    {
    }
}
