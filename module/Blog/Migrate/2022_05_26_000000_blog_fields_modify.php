<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class BlogFieldsModify extends Migration
{
    
    public function up()
    {
        Schema::table('blog', function (Blueprint $table) {

            $table->tinyInteger('isTop')->nullable()->comment('置顶');
            $table->integer('commentCount')->nullable()->comment('置顶');

        });
    }

    
    public function down()
    {

    }
}
