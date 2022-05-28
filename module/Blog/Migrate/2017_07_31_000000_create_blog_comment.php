<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBlogComment extends Migration
{
    
    public function up()
    {
        Schema::create('blog_comment', function (Blueprint $table) {

            $table->increments('id');
            $table->timestamps();

            $table->integer('blogId')->nullable()->comment('博客');

            $table->string('username', 200)->nullable()->comment('称呼');
            $table->string('email', 200)->nullable()->comment('邮箱');
            $table->string('url', 400)->nullable()->comment('网址');
            $table->string('content', 2000)->nullable()->comment('内容');

            $table->index(['blogId']);

        });
    }

    
    public function down()
    {

    }
}
