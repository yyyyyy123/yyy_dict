<?php

namespace App\Services\Column;

class ColumnObj
{
    public $column_name;

    public $column_comment;
    public $column_type;

    public $table_name;
    public $nullable;
    public $default;


    public function __construct($arr)
    {
        $this->setColumnName($arr['column_name'])->setTableName($arr['table_name'])
            ->setColumnComment($arr['column_comment']);
        // 处理列类型。
        $temp_type = $arr['column_type'];
        if ($arr['extra']) {
            $temp_type .= ' ' . $arr['extra'];
        }
        $this->setColumnType($temp_type);

        $this->setNullable(($arr['is_nullable'] == 'YES' ? 'null' : 'not null'));
        // 处理缺省值。
        $temp_default = $arr['default'];
        if (is_null($arr['default'])) {
            $temp_default = '(NULL)';
        }
        if ($temp_default === '') {
            $temp_default = '\'\'';
        }
        $this->setDefault($temp_default);
    }

    public function getArray()
    {
        return (array)$this;

    }



    /**
     * @return mixed
     */
    public function getColumnName()
    {
        return $this->column_name;
    }

    /**
     * @param mixed $columnName
     * @return ColumnObj
     */
    public function setColumnName($columnName): ColumnObj
    {
        $this->column_name = $columnName;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getColumnComment()
    {
        return $this->column_comment;
    }

    /**
     * @param mixed $columnComment
     * @return ColumnObj
     */
    public function setColumnComment($columnComment): ColumnObj
    {
        $this->column_comment = $columnComment;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getColumnType()
    {
        return $this->column_type;
    }

    /**
     * @param mixed $columnType
     * @return ColumnObj
     */
    public function setColumnType($columnType): ColumnObj
    {
        $this->column_type = $columnType;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTableName()
    {
        return $this->table_name;
    }

    /**
     * @param mixed $tableName
     * @return ColumnObj
     */
    public function setTableName($tableName): ColumnObj
    {
        $this->table_name = $tableName;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getNullable()
    {
        return $this->nullable;
    }

    /**
     * @param mixed $nullable
     * @return ColumnObj
     */
    public function setNullable($nullable): ColumnObj
    {
        $this->nullable = $nullable;
        return $this;
    }


    /**
     * @return mixed
     */
    public function getDefault()
    {
        return $this->default;
    }

    /**
     * @param mixed $default
     * @return ColumnObj
     */
    public function setDefault($default): ColumnObj
    {
        $this->default = $default;
        return $this;
    }


}
