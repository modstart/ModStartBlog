<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminUploadModstartChange extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('admin_upload', function (Blueprint $table) {

            $table->integer('userId')->nullable()->comment('');
            $table->integer('uploadCategoryId')->nullable()->comment('');
            $table->index(['userId', 'uploadCategoryId']);

        });
        Schema::table('admin_upload_category', function (Blueprint $table) {

            $table->integer('userId')->nullable()->comment('');
            $table->index(['userId', 'category']);

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
