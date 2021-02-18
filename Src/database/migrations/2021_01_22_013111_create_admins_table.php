<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_admin', function (Blueprint $table) {
            $table->increments('id')->comment('ID');
            $table->string('admin_code',12)->unique()->comment('管理账号');
            $table->string('name', 12)->comment('姓名');
            $table->string('id_code', 32)->comment('身份证号');
            $table->string('email', 64)->comment('邮件');
            $table->string('phone', 24)->comment('电话');
            $table->string('password', 64)->comment('密码');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_admin');
    }
}
