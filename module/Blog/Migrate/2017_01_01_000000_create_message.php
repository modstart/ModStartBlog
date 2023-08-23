<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateMessage extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('message', function (Blueprint $table) {

            $table->increments('id');
            $table->timestamps();

            $table->string('username', 200)->nullable()->comment('称呼');
            $table->string('email', 200)->nullable()->comment('邮箱');
            $table->string('url', 400)->nullable()->comment('网址');
            $table->string('content', 2000)->nullable()->comment('内容');

            $table->integer('upCount')->nullable()->comment('赞同数');
            $table->integer('downCount')->nullable()->comment('反对数');

            $table->string('reply', 2000)->nullable()->comment('作者回复');


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
