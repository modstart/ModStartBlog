<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class DataAddMd5 extends Migration
{
    
    public function up()
    {
        Schema::table('data', function (Blueprint $table) {
            $table->string('md5', 32)->nullable()->comment('');
            $table->index('md5');
        });
        Schema::table('data_temp', function (Blueprint $table) {
            $table->string('md5', 32)->nullable()->comment('');
            $table->index('md5');
        });
    }

    
    public function down()
    {

    }
}
