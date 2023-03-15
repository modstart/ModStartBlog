<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class BlogFieldsRecommendHotModify extends Migration
{
    
    public function up()
    {
        Schema::table('blog', function (Blueprint $table) {

            $table->tinyInteger('isHot')->nullable()->comment('热门');
            $table->tinyInteger('isRecommend')->nullable()->comment('推荐');

        });
    }

    
    public function down()
    {

    }
}
