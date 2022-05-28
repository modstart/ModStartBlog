<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class NavNavAddPid extends Migration
{
    
    public function up()
    {
        if (!\ModStart\Core\Dao\ModelManageUtil::hasTableColumn('nav', 'pid')) {
            Schema::table('nav', function (Blueprint $table) {
                $table->integer('pid')->nullable()->comment('上级ID');
            });
        }
        \ModStart\Core\Dao\ModelUtil::updateAll('nav', ['pid' => 0]);
    }

    
    public function down()
    {
    }
}