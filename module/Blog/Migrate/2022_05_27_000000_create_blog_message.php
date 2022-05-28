<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateBlogMessage extends Migration
{
    
    public function up()
    {
        Schema::rename('message', 'blog_message');

        Schema::table('blog_message', function (Blueprint $table) {
            $table->integer('memberUserId')->nullable()->comment('');
            $table->tinyInteger('status')->nullable()->comment('');
        });

        \ModStart\Core\Dao\ModelUtil::updateAll('blog_message', [
            'memberUserId' => 0,
            'status' => \Module\Blog\Type\BlogMessageStatus::VERIFY_SUCCESS,
        ]);
    }

    
    public function down()
    {

    }
}
