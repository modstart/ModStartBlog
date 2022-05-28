<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdmin extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('admin_role', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->string('name', 200)->comment('角色名称')->nullable();
        });

        Schema::create('admin_role_rule', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->unsignedInteger('roleId')->comment('角色ID')->nullable();
            $table->string('rule', 200)->comment('角色')->nullable();

            $table->index('roleId');
        });
        Schema::create('admin_user', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->string('username', 100)->comment('用户名')->nullable();
            $table->char('password', 32)->comment('密码')->nullable();
            $table->char('passwordSalt', 16)->comment('密码Salt')->nullable();
            $table->boolean('ruleChanged')->comment('权限是否有改变')->nullable();
            $table->timestamp('lastLoginTime')->comment('上次登录时间')->nullable();
            $table->string('lastLoginIp', 20)->comment('上次登录IP')->nullable();
            $table->timestamp('lastChangePwdTime')->comment('上次密码修改时间')->nullable();

            $table->index('username');

        });
        Schema::create('admin_user_role', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->unsignedInteger('userId')->nullable();
            $table->unsignedInteger('roleId')->nullable();

            $table->index('userId');
            $table->index('roleId');
        });

//        $passwordSalt = \Illuminate\Support\Str::random(16);
//
//        $adminUser = new \Edwin404\Admin\Models\AdminUser();
//        $adminUser->created_at = \Carbon\Carbon::now();
//        $adminUser->updated_at = \Carbon\Carbon::now();
//        $adminUser->username = config('env.ADMIN_INIT_USERNAME', 'admin');
//        $adminUser->passwordSalt = $passwordSalt;
//        $adminUser->password = md5(md5(config('env.ADMIN_INIT_USERNAME', '123456')) . md5($passwordSalt));
//        $adminUser->save();

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //Schema::drop('AdminRole');
        //Schema::drop('AdminRoleRule');
        //Schema::drop('AdminUser');
        //Schema::drop('AdminUserRole');
    }
}
