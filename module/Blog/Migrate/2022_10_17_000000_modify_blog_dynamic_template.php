<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class ModifyBlogDynamicTemplate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('blog_category', function (Blueprint $table) {
            $table->string('templateView', 50)->nullable()->comment('');
        });
        Schema::table('blog', function (Blueprint $table) {
            $table->string('templateView', 50)->nullable()->comment('');
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
