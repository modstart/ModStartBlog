<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateBlogCategory extends Migration
{
    
    public function up()
    {
        Schema::create('blog_category', function (Blueprint $table) {

            $table->increments('id');
            $table->timestamps();

            $table->integer('pid')->nullable()->comment('');
            $table->integer('sort')->nullable()->comment('');

            $table->string('title', 200)->nullable()->comment('');
            $table->integer('blogCount')->nullable()->comment('博客数');

        });

        Schema::table('blog', function (Blueprint $table) {
            $table->integer('categoryId')->nullable()->comment('');

            $table->index(['categoryId']);
        });
    }

    
    public function down()
    {

    }
}
