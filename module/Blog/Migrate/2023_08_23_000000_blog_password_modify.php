<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class BlogPasswordModify extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('blog', function (Blueprint $table) {

            /** @see \Module\Blog\Type\BlogVisitMode */
            $table->tinyInteger('visitMode')->nullable()->comment('访问模式');
            $table->string('visitPassword',20)->nullable()->comment('密码');

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
