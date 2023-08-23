<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class ModifyBlogCategoryKeywords extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('blog_category', function (Blueprint $table) {

            $table->string('cover', 200)->nullable()->comment('');
            $table->string('keywords', 200)->nullable()->comment('');
            $table->string('description', 400)->nullable()->comment('');

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
