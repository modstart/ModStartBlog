<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBlog extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blog', function (Blueprint $table) {

            $table->increments('id');
            $table->timestamps();

            $table->string('title', 200)->nullable()->comment('标题');
            $table->string('tag', 200)->nullable()->comment('标签');
            $table->string('summary', 400)->nullable()->comment('摘要');
            $table->string('images', 2000)->nullable()->comment('图片');
            $table->text('content')->nullable()->comment('内容');

            $table->string('seoKeywords', 200)->nullable()->comment('SEO关键词');
            $table->string('seoDescription', 400)->nullable()->comment('SEO描述');

            $table->tinyInteger('isPublished')->nullable()->comment('发布');
            $table->timestamp('postTime')->nullable()->comment('发布时间');
            $table->integer('clickCount')->nullable()->comment('点击数');

            $table->index(['postTime']);

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
