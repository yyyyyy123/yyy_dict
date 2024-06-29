<?php

namespace App\Http\Controllers;

use App\Services\DictService;
use App\Services\HtmlService;
use App\Services\ServiceKey;
use App\Services\ServiceSystem;
use DB;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Michelf\MarkdownExtra;

/**
 * yyy的数据字典。.
 */
class DictController extends Controller
{

    /**
     * 数据字典页面，普通模式。
     *
     */
    public function dict()
    {
        // 安全措施。
        $conf = config('database.connections');
        $keys = array_keys($conf);

        if (!is_file(database_path('database.sqlite'))) {
            echo "请在命令行下 执行 php artisan touch_db";
            return;
        }
        $connection = request()->input('connection', '');
        $connection = strval($connection);
        $service = new DictService($connection);

            $full = $service->getDataForAll();
        return view('dict', $full);
    }

    public function help()
    {
        $my_text = file_get_contents( app_path( 'help.md' ) );
        $my_html = MarkdownExtra::defaultTransform($my_text);
        echo $my_html;

    }




    /**
     * 编辑的首页，仅显示一些导航。
     * @param Request $request
     * @param HtmlService $htmlService
     * @return Application|Factory|View
     * @throws Exception
     */
    public function dictEditIndex(Request  $request, HtmlService $htmlService)
    {
        $connection = $request->input('connection', '');
        $connection = strval($connection);
        $service = DictService::initForEdit($connection);

            $full = $service->getDataForEditIndex();
           // return $full;


        $full['connections_data'] = $htmlService->getConnectionHtml( $full['connections'], $full['connection'] );
        $full['table_nav_data'] =$htmlService->getTableNavigationHtml( $full['table_arr'], $full['connection']  );
        return view("dictEditIndex", $full);
    }

    /**
     * 编辑页
     * @param Request $request
     * @param HtmlService $htmlService
     * @return Application|Factory|View
     * @throws Exception
     */
    public function dictEditPage(Request  $request, HtmlService $htmlService)
    {
        $connection = $request->input('connection', '');
        $table_name = $request->input('table_name', '');

        $service = DictService::initForEdit($connection);
        // 先获取页面其他数据。
        $full = $service->getDataForEditIndex();
        $full['connections_data'] = $htmlService->getConnectionHtml( $full['connections'], $full['connection'] );
        $full['table_nav_data'] =$htmlService->getTableNavigationHtml( $full['table_arr'], $full['connection']  );
        // 再获取表数据。
        $table = collect( $full['table_arr'] )->filter(function ($item)use($table_name){
            return $item['table_name'] == $table_name;
        });
        if ($table->isEmpty()) {
            throw new Exception('参数错误');
        }
        $table = $table->first();
        $table = $service->addIndexToTableSingle( $table );
        $full['table_data'] = $htmlService->getTableSingleHtml(  $table,$connection, $table_name );
        return view("dictEditPage", $full);
    }



}
