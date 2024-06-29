<?php

namespace App\Http\Controllers;

use App\Http\Requests\ConnectionAddRequest;
use App\Http\Requests\ConnectionRemoveRequest;
use App\Http\Requests\ModifyTableRequest;
use App\Models\ConnectionModel;
use App\Models\MyColumnLogModel;
use App\Models\MyColumnModel;
use App\Models\MyTableLogModel;
use App\Models\MyTableModel;
use App\Services\ConnectionService;
use App\Services\ServiceKey;
use App\Services\ServiceSystem;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * yyy的数据字典。
 * 修改本地的 sqlite数据库。
 */
class ModifyController extends Controller
{

    /**
     * 加连接。
     * @param ConnectionAddRequest $request
     * @return array
     */
    public function addConnection(ConnectionAddRequest $request, ConnectionService $connectionService): array
    {
        $params = $request->validated();

        ConnectionModel::Create(
            [
                'connection'=>$params['connection'],
                'host'=>$params['host'],
                'port'=>$params['port'],
                'db_name'=>$params['db_name'],
                'username'=>$params['username'],
                'password'=>$params['password'],

            ]
        );
        $connectionService->createConfig();

        return [
            'status'=>0,
            'msg'=>'连接添加成功',
            'data'=>[
               // "table_comment"=> $params['table_comment']
            ],
        ];

    }

    /**
     * 删除连接。
     * @param ConnectionAddRequest $request
     * @return array
     */
    public function removeConnection(ConnectionRemoveRequest $request, ConnectionService $connectionService): array
    {
        $params = $request->validated();
        $connection = $params['connection'];

        DB::transaction(function () use($connection){
            ConnectionModel::query()->where('connection', $connection)->delete();
            MyTableModel::query()->where('connection_name', $connection)->delete();
            MyTableLogModel::query()->where('connection_name', $connection)->delete();

            MyColumnModel::query()->where('connection_name', $connection)->delete();
            MyColumnLogModel::query()->where('connection_name', $connection)->delete();
        });

        $connectionService->createConfig();

        return [
            'status'=>0,
            'msg'=>'连接删除成功',
            'data'=>[
            ],
        ];

    }


    /**
     * 改表注释。
     * @param ModifyTableRequest $request
     * @return array
     */
    public function modifyTable(ModifyTableRequest $request): array
    {
        $params = $request->validated();

        MyTableModel::updateOrCreate(
            [
                'connection_name'=>$params['connection_name'],
                'table_name'=>$params['table_name'],
            ],
            [ 'table_comment'=>$params['table_comment'],]
        );
        MyTableLogModel::create([
            'connection_name'=>$params['connection_name'],
            'table_name'=>$params['table_name'],
            'table_comment'=>$params['table_comment'],

        ]);
        return [
            'status'=>0,
            'msg'=>'修改成功',
            'data'=>[
                "table_comment"=> $params['table_comment']
            ],
        ];

    }


    /**
     * 改字段注释。
     *
     * @param ModifyTableRequest $request
     * @return array
     */
    public function modifyColumn(): array
    {

        $params = request()->input();

        Log::info($params);
//        [2024-06-26 17:00:03] local.INFO: array (
//        'column_name' => 'id',
//        'column_type' => 'int(10) auto_increment',
//        'column_comment' => '56tt▒| 好',
//        'default' => '(NULL)',
//        'nullable' => 'not null',
//        'connection_name' => 'test1',
//        'table_name' => 'eb_agent_level2',


        MyColumnModel::updateOrCreate(
            [
                'connection_name'=>$params['connection_name'],
                'table_name'=>$params['table_name'],
                'column_name'=>$params['column_name'],
            ],
            [ 'column_comment'=>$params['column_comment'],]
        );

        MyColumnLogModel ::create([
            'connection_name'=>$params['connection_name'],
            'table_name'=>$params['table_name'],
            'column_comment'=>$params['column_comment'],
            'column_name'=>$params['column_name'],
        ]);

        return [
            'status'=>0,
            'msg'=>'修改成功',
            'data'=>[],
        ];

    }

}
