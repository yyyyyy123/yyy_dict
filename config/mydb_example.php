<?php
/**
 * 如果您的数据库超过一个 ，请将本文件复制，并命名为mydb.php，放在同个目录下。
 *
 * 注意，不要把默认的数据库连接放这里，这里只放第2个库以及之后的库。默认的依然配置在.env，且必须有效。
 */


return [

    'mydb_foo' => [
        'driver' => 'mysql',// 这个不能改变。
        'host' => 'ip或域名', // 要改
        'port' => '3306',// 要改
        'database' => '我的数据库名',// 要改
        'username' => 'root',// 要改
        'password' => '123456',// 要改
        'charset' => 'utf8mb4',
    ],

    'mydb_bar' => [
        'driver' => 'mysql',// 这个不能改变。
        'host' => 'ip或域名', // 要改
        'port' => '3306',// 要改
        'database' => '我的数据库名',// 要改
        'username' => 'root',// 要改
        'password' => '123456',// 要改
        'charset' => 'utf8mb4',
    ],


];

