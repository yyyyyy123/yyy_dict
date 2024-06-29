<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

/**
 * 检查项目是否具备条件执行。
 *
 */
class ProjectCheckService
{
    protected $err;

    public function setErr($msg)
    {
        $this->err = $msg;
    }

    public function getErr(){
        return $this->err;
    }


    /**
     * 执行检查。
     * @return bool
     */
    public function handle():bool
    {
        // 查.env 是否存在。
        // 查 数据库文件是否存在。
        // 查 连接键中是否包含mysql。
        // 查 连接键中是否有非法字符。
        //
        if ( !file_exists(base_path('.env'))){
            $this->setErr("您需要先配置项目的.env 文件");
            return false;
        }
        if (!is_file(database_path('database.sqlite'))) {
            $this->setErr( "请在命令行下 执行 php artisan touch_db");
            return false;
        }
        if ( !is_file( config_path( 'mydb.php' ) ) ){
            $this->setErr( "请在命令行下 执行 php artisan touch_db");
            return false;
        }


    }


}
