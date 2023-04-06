<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use ModStart\Core\Dao\ModelUtil;
use Module\Banner\Type\BannerType;

class ModifyBannerAddBackgroundColor extends Migration
{
    
    public function up()
    {
        Schema::table('banner', function (Blueprint $table) {
            $table->string('backgroundColor', 20)->nullable()->comment('背景色');
        });
    }

    
    public function down()
    {
    }
}
