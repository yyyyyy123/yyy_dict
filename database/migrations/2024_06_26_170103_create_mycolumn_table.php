<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMycolumnTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mycolumn', function (Blueprint $table) {
            $table->id();

            $table->string('connection_name')->index()->default('')->comment('连接的名称');
            $table->string('table_name')->index()->default('')->comment('表名称');
            $table->string('column_name')->index()->default('')->comment('列名称');
            $table->string('column_comment', 1000)->default('')->comment('列注释');
            $table->integer('user_id')->default(0)->comment('用户id');

            $table->timestamps();
        });

        Schema::create('mycolumn_logs', function (Blueprint $table) {
            $table->id();

            $table->string('connection_name')->index()->default('')->comment('连接的名称');
            $table->string('table_name')->index()->default('')->comment('表名称');
            $table->string('column_name')->index()->default('')->comment('列名称');
            $table->string('column_comment', 1000)->default('')->comment('列注释');
            $table->integer('user_id')->default(0)->comment('用户id');
            $table->string('old', 1000)->default('')->comment('原有的列注释');
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
        Schema::dropIfExists('mycolumn');
        Schema::dropIfExists('mycolumn_logs');

    }
}
