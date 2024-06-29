<?php

namespace App\Http\Controllers;

use App\Repository\SqliteRepository;
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

/**
 * yyy的数据字典。.
 */
class ConnectionController extends Controller
{

    /**
     * 添加连接页
     * @param Request $request
     * @param HtmlService $htmlService
     * @return Application|Factory|View
     * @throws Exception
     */
    public function connectionAddPage(Request  $request, HtmlService $htmlService)
    {
        $connection = $request->input('connection', '');
        //$table_name = $request->input('table_name', '');

        $service = DictService::initForEdit($connection);
        $full = $service->getDataForEditIndex();
        $full['connections_data'] = $htmlService->getConnectionHtml( $full['connections'], $full['connection'] );

        return view("connectionAddPage", $full);
    }


    /**
     * 删除连接页
     * @param Request $request
     * @param HtmlService $htmlService
     * @return Application|Factory|View
     * @throws Exception
     */
    public function connectionRemovePage(Request  $request, HtmlService $htmlService,SqliteRepository $sqliteRepository)
    {
        $connection = $request->input('connection');
        $allConnections = $sqliteRepository->getAllConnections();
        $find=0;
        $connectionInfo =null;
        foreach ($allConnections as $every) {
            if ($every->connection == $connection) {
                $find=1;
                $connectionInfo = $every->toArray();
                break;
            }
        }
        if (!$find) {
            throw new Exception('连接参数不存在，错误');
        }


        $service = DictService::initForEdit($connection);
        $full = $service->getDataForEditIndex();
        //  return  $full;
        $full['connections_data'] = $htmlService->getConnectionHtml( $full['connections'], $full['connection'] );
        $full['connections_info'] = $connectionInfo;

        return view("connectionRemovePage", $full);
    }

}
