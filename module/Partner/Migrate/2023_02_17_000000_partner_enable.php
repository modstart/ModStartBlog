<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use ModStart\Core\Dao\ModelUtil;

class PartnerEnable extends Migration
{
    
    public function up()
    {
        Schema::table('partner', function (Blueprint $table) {
            $table->tinyInteger('enable')->nullable()->comment('');
        });
        ModelUtil::updateAll('partner', ['enable' => true]);
    }

    
    public function down()
    {

    }
}
