<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class NavNavIcon extends Migration
{
    
    public function up()
    {
        Schema::table('nav', function (Blueprint $table) {
            $table->string('icon', 50)->nullable()->comment('图标');
        });
    }

    
    public function down()
    {
    }
}
