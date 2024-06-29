<?php

namespace App\Repository;

use App\Models\ConnectionModel;
use App\Models\MyColumnModel;
use App\Models\MyTableModel;
use DB;
use Illuminate\Support\Collection;

/**
 * 查本项目自己的 sqlit 数据库。
 * 主要存储 表注释和 字段的注释。
 */
class SqliteRepository
{

    protected $myTableModel;
    protected $myColumnModel;
    protected $connectionModel;



    public function __construct()
    {
        $this->myTableModel = new  MyTableModel();
        $this->myColumnModel = new  MyColumnModel();
        $this->connectionModel = new ConnectionModel();

    }

    /**
     * 查某连接的全部表注释。
     * @param $connection string
     * @return Collection
     */
    public function getTableCommentByConnection(string $connection): Collection
    {
        return $this->myTableModel->newQuery()->where('connection_name', $connection)->get();
    }

    /**
     * 查某连接的全部的字段注释。
     * @param $connection string
     * @return Collection
     */
    public function getColumnCommentByConnection(string $connection): Collection
    {
        return $this->myColumnModel->newQuery()->where('connection_name', $connection)->get();

    }

    /**
     * 查全部的连接
     * @param $connection string
     * @return Collection
     */
    public function getAllConnections(): Collection
    {
        return $this->connectionModel->newQuery()->get();

    }


}
