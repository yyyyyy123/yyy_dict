<?php

namespace App\Repository;

use DB;
use Illuminate\Support\Collection;

/**
 * 查用户数据库的 sql
 * 目前只支持 mysql，假定用户使用的都是 mysql
 */
class MysqlRepository
{
    /**
     * 查表的大概信息。
     * @param $connection string
     * @param $db_name string
     * @return Collection
     */
    public function getTableBasicInfo(string $connection, string $db_name): Collection
    {
        $sql = "SELECT TABLE_NAME AS 'table_name',
                       TABLE_COMMENT AS 'table_comment',
                       ENGINE AS 'engine',
                       TABLE_ROWS AS 'table_rows'
                  FROM information_schema.tables
                 WHERE table_schema = ?
                   AND table_type='BASE TABLE'
                 ";

        return collect(DB::connection($connection)->select($sql, [$db_name]))
            ->map(function ($item) {
                return (array)$item;
            });
    }

    /**
     * 查表的详细信息。
     * @param $connection string
     * @param $db_name string
     * @return Collection
     */
    public function getTableColumnInfo(string $connection, string $db_name): Collection
    {
        $sql = "
            SELECT
                T.TABLE_NAME AS 'table_name',

                C.COLUMN_NAME AS 'column_name',
                C.COLUMN_TYPE AS 'column_type',
                C.COLUMN_COMMENT AS 'column_comment',
                C.IS_NULLABLE AS 'is_nullable',
                C.EXTRA AS 'extra',
                C.COLUMN_DEFAULT AS 'default'
             FROM information_schema.COLUMNS C
            INNER JOIN information_schema.TABLES T
               ON C.TABLE_SCHEMA = T.TABLE_SCHEMA
              AND C.TABLE_NAME = T.TABLE_NAME
            WHERE T.TABLE_SCHEMA = ?
              AND T.TABLE_TYPE = 'BASE TABLE'
        ";
        return collect(DB::connection($connection)->select($sql, [$db_name]))
            ->map(function ($item) {
                return (array)$item;
            });
    }

    /**
     * 查某个表的建表语句。
     * @param string $connection
     * @param string $table_name
     * @return string
     */
    public function showCreateTable(string $connection, string $table_name):string
    {
        $results = DB::connection($connection)->select('show create table `'. $table_name .'`');
        return get_object_vars($results[0])['Create Table'];

    }

}
