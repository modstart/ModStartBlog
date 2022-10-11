<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class DataAddIndex extends Migration
{
    
    public function up()
    {
        Schema::table('data', function (Blueprint $table) {
            $table->index(['category', 'path']);
        });
    }

    
    public function down()
    {

    }
}
