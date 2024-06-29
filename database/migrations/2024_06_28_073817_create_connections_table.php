<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConnectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('connections', function (Blueprint $table) {
            $table->id();

            $table->integer('user_id')->default(0)->comment('用户id');
            $table->string('connection')->default('')->unique()->comment('连接名，唯一');
            $table->string('host')->default('')->comment('主机或域名');
            $table->string('port')->default('3306')->comment('端口');
            $table->string('db_name')->default('')->comment('库名');
            $table->string('username')->default('')->comment('账号');
            $table->string('password')->default('')->comment('密码');


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
        Schema::dropIfExists('connections');
    }
}
