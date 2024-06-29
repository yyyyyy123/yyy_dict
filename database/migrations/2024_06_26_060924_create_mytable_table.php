<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMytableTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mytable', function (Blueprint $table) {
            $table->id();

            $table->string('connection_name')->index() ->default('')->comment('连接的名称');
            $table->string('table_name')->index()->default('')->comment('表名称');
            $table->string('table_comment', 1000)->default('')->comment('表注释');
            $table->integer('user_id')->default(0)->comment('用户id');

            $table->timestamps();
        });

        Schema::create('mytable_logs', function (Blueprint $table) {
            $table->id();

            $table->string('connection_name')->index()->default('')->comment('连接的名称');
            $table->string('table_name')->index()->default('')->comment('表名称');
            $table->string('table_comment', 1000)->default('')->comment('表注释');
            $table->string('old', 1000)->default('')->comment('原有的表注释');
            $table->integer('user_id')->default(0)->comment('用户id');


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
        Schema::dropIfExists('mytable');
        Schema::dropIfExists('mytable_logs');
    }

}
