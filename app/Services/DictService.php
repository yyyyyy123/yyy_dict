<?php

namespace App\Services;

use App\Repository\MysqlRepository;
use App\Repository\MyTableRepository;
use App\Repository\SqliteRepository;
use App\Services\Column\ColumnObj;
use App\Services\Column\IndexObj;
use DB;
use Exception;
use Illuminate\Support\Facades\Log;

/**
 * 数据字典服务
 *
 * 主要通过查询数据库，获得数据字典的内容。
 * 包括查客户的数据库和sqlite数据库。
 */
class DictService
{
    /**
     * @var string 当前的库名
     */
    public $db_name;

    /**
     * @var string 当前的连接。
     */
    public $connection;

    /**
     * @var array 全部的连接。
     */
    public $connections;

    /**
     * @var MysqlRepository 数据仓库。客户的数据库。
     */
    protected $mysqlRepository;

    /**
     * @var SqliteRepository 本项目自身的sqlite
     */
    protected $sqliteRepository;

    /**
     * 被控制器调用，
     *
     * 同时确定了当前的连接，库名。
     * @param $connection string 连接名，可能空
     * @throws Exception
     */
    public function __construct(string $connection)
    {
        $this->mysqlRepository = new MysqlRepository();
        $this->sqliteRepository = new SqliteRepository();
        $this->connections = $this->getConnections();
        if ($connection) {
            $find = false;
            $this->connection = $connection;
            foreach ($this->connections as $v) {
                if ($v['connection'] == $connection) {
                    $this->db_name = $v['db_name'];
                    $find = true;
                    break;
                }
            }
            if (!$find) {
                throw new Exception('连接名称错误');
            }

        } else {
            //如果用户什么都不传，我就读取第一个连接，通常也是.env配置的那个
            foreach ($this->connections as $v) {
                $this->connection = $v['connection'];
                $this->db_name = $v['db_name'];
                break;
            }
        }
    }

    /**
     * 这是专门给编辑页使用的。
     * @throws Exception
     */
    public static function initForEdit(string $connection): DictService
    {
        $service = new DictService($connection);
        if (!$connection) {
            $service->connection='';
            $service->db_name='';
        }
        return  $service;
    }

    /**
     * 获取全部的连接，仅仅从用户的配置 database.php 和 mydb.php里获得。
     *
     * @return array 二维数组。所有的连接，包括键 [ 'connection', 'db_name' ]
     */
    protected function getConnections(): array
    {
        $result = [];
        foreach (config('database.connections') as $k => $v) {
            //本项目只处理mysql
            if ($v['driver'] == 'mysql') {
                $result[] = [
                    'connection' => $k,
                    'db_name' => $v['database'],
                ];
            }
        }
        return $result;
    }

    /**
     * 返回所有需要的数据，全部的。
     *
     * @return array 包括键 ['db_name', 'connection', 'table_arr', 'connections', ]
     */
    public function getDataForAll(): array
    {
        if (!$this->connections) {
            return [
                'db_name' => '',
                'connection' => '',
                'connections' => [],
                'table_arr' => [],
            ];
        }
        $result = $this->getDataForEditIndex();
        $result['table_arr'] = $this->addIndexToTableArr($result['table_arr']);
        return $result;
    }

    /**
     * 仅返回表的数据。不去查每个表的 show create table
     * @return array 包括键 ['db_name', 'connection', 'table_arr', 'connections', ]
     */
    public function getDataForEditIndex(): array
    {
        if (!$this->connections) {
            return [
                'db_name' => '',
                'connection' => '',
                'connections' => [],
                'table_arr' => [],
            ];
        }
        if ($this->connection) {
            $table_arr = $this->getTableInit();
            $table_arr = $this->addColumnToTable($table_arr);// 获取列名，列注释，缺省值等等。
        }else{
            $table_arr=[];
        }
        return [
            'db_name' => $this->db_name,
            'connection' => $this->connection,
            'connections' => $this->connections,
            'table_arr' => $table_arr,
        ];
    }


    /**
     * 查出初步的表信息。
     * 方法：先查客户数据库 ，再结合自己的sqlite保存的数据。
     * @return array 二维数组，包括键 ['table_name', 'table_comment', 'engine', 'table_rows', 'no',  ]
     */
    protected function getTableInit(): array
    {
        // 这是保存的用户修改的表注释
        $hasModifiedTableCommentList = $this->sqliteRepository->getTableCommentByConnection($this->connection);

        // 先查出表的元数据，和字段的元数据。
        return $this->mysqlRepository->getTableBasicInfo($this->connection, $this->db_name)
            ->map(function ($item, $k) use ($hasModifiedTableCommentList) {
                //重要，这里加上索引。人为指定的序号。
                $item['no'] = $k + 1;

                // 这里检索，如果查到有用户保存的表定义，就替换。
                $key = $hasModifiedTableCommentList->search(function ($item2) use ($item) {
                    return $item['table_name'] == $item2['table_name'];
                });
                if ($key !== false) {
                    $item['table_comment'] = $hasModifiedTableCommentList->get($key)['table_comment'];
                }
                return $item;
            })->toArray();
    }

    /**
     * 这是返回一个一维数组。包括一个表的各种信息。
     *
     * @param $table array 包括键 ['table_name', 'table_comment', 'engine', 'table_rows', 'no', 'columns', ]
     * @return array 包括键 ['table_name', 'table_comment', 'engine', 'table_rows', 'no', 'columns',
     *                                'indexs', 'primary'   ]
     */
    public function addIndexToTableSingle(array $table): array
    {
        $createSentence = $this->mysqlRepository->showCreateTable($this->connection, $table['table_name']);
        $arrIndex = (new  IndexObj($createSentence))->getArray();
        return array_merge($table, $arrIndex);
    }

    /**
     * 给大表加索引数据。并返回。
     * 特别注意，每个表都查了一遍 mysql 命令，但还好，速度快。
     *
     * @param $table_arr array
     * @return array 二维数组，包括键 ['table_name', 'table_comment', 'engine', 'table_rows', 'no', 'columns',
     *                               'indexs', 'primary'   ]
     */
    protected function addIndexToTableArr(array $table_arr): array
    {
        return collect($table_arr)->map(function ($table) {
            return $this->addIndexToTableSingle($table);
        })->all();
    }

    /**
     * 给大表加上列的数据。
     * @param $table_arr array
     * @return array 二维数组，包括键 ['table_name', 'table_comment', 'engine', 'table_rows', 'no', 'columns' ]
     */
    protected function addColumnToTable(array $table_arr): array
    {

        //获取用户保存数据。
        $hasModifiedTableCommentList = $this->sqliteRepository->getColumnCommentByConnection($this->connection);

        //1、从数据库查到的原始列的数据，过滤一遍，
        //2、再合并用户保存的数据。现在列数据完整了。
        //   合并依据是 表名和列名同时，注意啊，不同的表可是有同名的列的！！
        $column_arr = $this->mysqlRepository->getTableColumnInfo($this->connection, $this->db_name)
            ->map(function ($arr) {
                return (new ColumnObj($arr))->getArray();
            })
            ->map(function ($item) use ($hasModifiedTableCommentList) {
                $new = $item;
                foreach ($hasModifiedTableCommentList as $v) {
                    if ($v['table_name'] == $item['table_name'] && $v['column_name'] == $item['column_name']) {
                        $new['column_comment'] = $v['column_comment'];
                        break;
                    }
                }
                return $new;
            })
            ->sortBy('column_name')
            ->values();

        // 3、把列数据合并到大表中。最后返回。
        return collect($table_arr)->map(function ($table) use ($column_arr) {
            $table['columns'] = $column_arr->filter(function ($column) use ($table) {
                return $column['table_name'] == $table['table_name'];
            })->all();
            return $table;
        })->all();
    }


}
