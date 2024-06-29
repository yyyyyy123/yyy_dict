<?php

namespace App\Repository;

use DB;
use Illuminate\Support\Facades\Log;
use PDO;
use PDOException;

/**
 * 验证用户填写的连接是否正确。
 * 主要存储 表注释和 字段的注释。
 */
class PdoHelp
{

    protected $connection;
    protected $host;
    protected $port;
    protected $db_name;
    protected $username;
    protected $password;

    // 一些正确的信息。
    protected $info = '';

    // 错误信息。
    protected $err;

    public function setErr($msg)
    {
        $this->err = $msg;
    }

    public function getErr()
    {
        if ( preg_match( '#Connection refused#', $this->err ) ){
            $this->err .= '，错误：连接被拒绝。';
        }elseif ( preg_match( '#Access denied for user#', $this->err ) ){
            $this->err .= '，错误：您的账号和密码填写有错误';
        }
        elseif ( preg_match( '#Unknown database#', $this->err ) ){
            $this->err .= '，错误：您数据库名填写错误';
        }

        return $this->err;
    }

    public function getInfo()
    {
        return $this->info;
    }


    public function __construct($host, $port, $db_name, $username, $password)
    {
        $this->host = $host;
        $this->port = $port;
        $this->db_name = $db_name;
        $this->username = $username;
        $this->password = $password;

    }

    protected function getDsn()
    {
        return 'mysql:host=' . $this->host .
            ';dbname=' . $this->db_name . ';port=' . $this->port . ';charset=utf8';
    }

    /**
     * 检测数据库
     * @return bool
     */
    public function isValid(): bool
    {
        $result = $this->canConnection();
        if (!$result) {
            return false;
        }
        $result = $this->canReadInformationSchema();
        if (!$result) {
            return false;
        }
        return true;
    }

    /**
     * 检测能否提供连接。
     * @return bool
     */
    protected function canConnection(): bool
    {
        try {
            //数据源:代表连接那种数据库，数据库是什么。数据库管理工具的账号+密码
            $pdo = new PDO($this->getDsn(), $this->username, $this->password);

            // 设置错误模式为异常
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->exec('set names utf8');
        } catch (PDOException $e) {
            $this->setErr($e->getMessage());
            return false;
        }
        $pdo = null;
        return true;
    }

    /**
     * 查能否连到库。
     * @return bool
     */
    protected function canReadInformationSchema(): bool
    {
        try {
            //数据源:代表连接那种数据库，数据库是什么。数据库管理工具的账号+密码
            $pdo = new PDO($this->getDsn(), $this->username, $this->password);

            // 设置错误模式为异常
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->exec('set names utf8');

            $sql = "
                 SELECT * FROM information_schema.tables
                  WHERE table_schema = ?
            ";
            $sth = $pdo->prepare($sql);
            $sth->bindValue(1, $this->db_name, PDO::PARAM_STR);
            $sth->execute();
            $result = $sth->fetchAll(PDO::FETCH_ASSOC);
            Log::info('库检测到多少行：' . count($result));
            $this->info = '库检测到多少行：' . count($result);

        } catch (PDOException $e) {
            $this->setErr($e->getMessage());
            return false;
        }
        $pdo = null;
        return true;
    }

}
