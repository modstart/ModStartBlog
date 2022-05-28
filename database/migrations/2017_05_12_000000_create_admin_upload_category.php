<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminUploadCategory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_upload_category', function (Blueprint $table) {

            $table->increments('id');
            $table->timestamps();

            $table->string('category', 10)->nullable()->comment('大类');

            $table->integer('pid')->nullable()->comment('上级分类');
            $table->integer('sort')->nullable()->comment('排序');
            $table->string('title', 50)->nullable()->comment('名称');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
}
