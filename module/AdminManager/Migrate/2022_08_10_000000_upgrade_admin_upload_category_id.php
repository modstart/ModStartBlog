<?php

use Illuminate\Database\Migrations\Migration;
use ModStart\Core\Dao\ModelUtil;

class UpgradeAdminUploadCategoryId extends Migration
{
    
    public function up()
    {
        ModelUtil::update('admin_upload', [
            'uploadCategoryId' => 0,
        ], [
            'uploadCategoryId' => -1,
        ]);
    }

    
    public function down()
    {
    }
}
