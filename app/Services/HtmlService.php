<?php

namespace App\Services;

use App\Repository\MysqlRepository;
use App\Repository\MyTableRepository;
use App\Repository\SqliteRepository;
use App\Services\Column\ColumnObj;
use App\Services\Column\IndexObj;
use DB;
use Illuminate\Support\Facades\Log;

/**
 * 数据字典服务
 *
 * 主要通过查询数据库，获得数据字典的内容。
 * 包括查客户的数据库和sqlite数据库。
 */
class HtmlService
{
    /**
     * 连接的导航html
     * @param array $connections
     * @param string $connection
     * @return string
     */
    public function getConnectionHtml(array $connections, string $connection):string
    {
        if (!$connections) {
            return '[]';
        }
        $cons = [];
        foreach ($connections as $key => $value) {
            $color = $value['connection'] == $connection ? "" : "text-dark";
            $temp = [
                "type" => "link",
                "href" => "/dictEditIndex?connection=" . $value['connection'],
                'body' => ($key + 1) . '. ' . $value['connection'],
                "className" => $color . ' hover:text-info hover:bg-blue-200 block',
                "blank" => false
            ];
            $temp2 = [
                'type' => "wrapper",
                "className" => "w-1/5  p-2 m-0 border border-dashed border-gray-300 font-bold",
                "body" => $temp,
            ];

            $cons[] = $temp2;

        }
        return json_encode($cons, JSON_UNESCAPED_UNICODE);
    }

    /**
     * 表的导航的html
     * @param array $table_arr
     * @param string $connection
     * @return string
     */
    public function getTableNavigationHtml(array $table_arr, string $connection):string
    {
        if (!$connection) {
            return '[]';
        }

        $tables_left = [];
        foreach ($table_arr as $value) {
            $temp = [
                "type" => "link",
                "href" => "/dictEditPage?connection=" . $connection .
                    '&table_name=' . $value['table_name'] . '#header2345',
                'body' => $value['no'] . '. ' . $value['table_name'] . "(" . $value['table_comment'] . ")",
                "className" => 'text-dark hover:text-info hover:bg-blue-200 block',

                "blank" => false
            ];
            $temp2 = [
                'type' => "wrapper",
                "className" => "  p-2 m-0 border border-dashed border-gray-300 font-bold",
                "style" => [
                    "width" => "19%",

                ],
                "body" => $temp,
            ];

            $tables_left[] = $temp2;

        }
        return json_encode($tables_left, JSON_UNESCAPED_UNICODE);

    }

    /**
     * 获取 编辑页面的表的html
     *
     * @param array $table
     * @param string $connection
     * @param string $table_name
     * @return string
     */
    public function getTableSingleHtml(array $table, string $connection, string $table_name):string
    {
        $temp = [];
        $temp['type'] = 'table2';

        $temp["columnsTogglable"] = false;
        $temp['bordered'] = false;
        $temp['rowClassNameExpr'] = 'group font-bold';
        $temp['data'] = [
            "table_comment" => $table['table_comment']
        ];


        // 设置表格的标题。
        $tableHeaderWord = [
            "type" => "tpl",
            "className" => "text-2xl",
            "tpl" =>
                "{$table['no']}.  {$table['table_name']}（\${table_comment}）（主键：{$table['primary']}）" .
                "（引擎：{$table['engine']}）"
        ];
        $button = [
            "type" => "button",
            "label" => "改表名注释",
            "actionType" => "dialog",
            "dialog" => [
                "title" => "改表名注释",
                "body" => [
                    "type" => "form",
                    "reload" => "window",
                    "api" => route('modifyTable'),
                    "body" => [
                        [
                            "type" => "input-text",
                            "value" => $table['table_comment'],
                            "name" => "table_comment",
                            "label" => "文本"
                        ],
                        [
                            "type" => "hidden",
                            "value" => $table['table_name'],
                            "name" => "table_name",
                        ],
                        [
                            "type" => "hidden",
                            "value" => $connection,
                            "name" => "connection_name",
                        ],
                        [
                            "type" => "hidden",
                            "value" => $table['table_comment'],
                            "name" => "old",
                        ],
                    ] //  end form  body
                ] // end dialog  body
            ] // end dialog
        ]; //end button 改表名的按钮。

        $tableHeader = [
            "type" => "wrapper",
            "body" => [
                $tableHeaderWord,
                $button,
            ],
        ];

        $temp['title'] = $tableHeader; // 现在，头部 设置好了。

        // 设置表格的末尾，主要是索引显示。
        $html = '';
        if (isset($table['indexs']) && $table['indexs']) {
            $html .= "<ul style='margin:0'>";
            foreach ($table['indexs'] as $vvv) {
                $html .= "<li>{$vvv}</li>";
            }
            $html .= "</ul>";
        }
        $temp['footer'] = [            // 现在，尾部 设置好了。
            "type" => "html",
            "html" => $html,
        ];
        $temp['quickSaveItemApi'] = [
            "url" => route('modifyColumn'),
            "method" => "post",
        ];

        // 设置表格的字段标题，都是汉字。
        $temp['columns'] = [
            [
                "title" => " ",
                "name" => "my_index",
                "width" => "3%",
                "titleClassName" => "font-bold text-center  text-lg",
            ],
            [
                "title" => "字段",
                "name" => "column_name",
                "width" => "10%",
                "titleClassName" => "font-bold text-center  text-lg",
                "className" => "\${index %2==0 ? 'group-hover:bg-dark group-hover:text-light':'xx1 group-hover:bg-dark group-hover:text-light' }",
            ],
            [
                "title" => "类型",
                "name" => "column_type",
                "width" => "10%",
                "titleClassName" => "font-bold text-center  text-lg",
                "className" => "\${index %2==0 ? '':'xx1 ' }",
            ],
            [
                "title" => "允许空",
                "name" => "nullable",
                "width" => "10%",
                "titleClassName" => "font-bold text-center  text-lg",
                "className" => "\${index %2==0 ? '':'xx1 ' }",
            ],
            [
                "title" => "默认值",
                "name" => "default",
                "width" => "10%",
                "titleClassName" => "font-bold text-center  text-lg",
                "className" => "\${index %2==0 ? '':'xx1 ' }",
            ],
            [
                "title" => "注释（点击图标可修改）",
                "name" => "column_comment",
                "width" => "50%",
                "titleClassName" => "font-bold text-center  text-lg",
                "className" => "\${index %2==0 ? 'group-hover:bg-dark group-hover:text-light':'xx1 group-hover:bg-dark group-hover:text-light' }",
                "quickEdit" => [
                    "type" => "textarea",
                    "saveImmediately" => true,
                    "showCounter" => true,
                    "minRows" => 5,
                    "style" => [
                        "width" => "800px"
                    ]
                ],
            ],
        ];

        // 设置表格的主体，列的部分。
        $temp['items'] = [];
        foreach ($table['columns'] as $k => $column) {
            $temp['items'] [] = [
                'column_name' => $column['column_name'],
                'column_type' => $column['column_type'],
                'column_comment' => $column['column_comment'],
                'default' => $column['default'],
                'nullable' => $column['nullable'],

                'connection_name' => $connection,
                'table_name' => $table_name,
                'my_index' => $k + 1,
            ];
        }
        return json_encode($temp, JSON_UNESCAPED_UNICODE);
    }


}
