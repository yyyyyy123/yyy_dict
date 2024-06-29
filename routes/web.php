<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DictController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\ModifyController;
use App\Http\Controllers\ConnectionController;

// 首页自动重定向
Route::get('/', function () {
    return redirect()->route('dict');
});

Route::any('/test', [TestController::class, 'index']);
Route::any('/test_table1', [TestController::class, 'test_table1']);
Route::any('/test_table2', [TestController::class, 'test_table2']);


// 数据字典展示页
Route::any('/dict', [DictController::class, 'dict'])->name('dict');
// 帮助页
Route::any('/help', [DictController::class, 'help'])->name('help');


// 编辑模式 - 编辑导航
Route::any('/dictEditIndex', [DictController::class, 'dictEditIndex'])->name('dictEditIndex');
// 编辑模式 - 编辑页
Route::any('/dictEditPage', [DictController::class, 'dictEditPage'])->name('dictEditPage');
// 编辑模式 - 添加连接页
Route::any('/connectionAddPage', [ConnectionController::class, 'connectionAddPage'])->name('connectionAddPage');
// 编辑模式 - 删除连接页
Route::any('/connectionRemovePage', [ConnectionController::class, 'connectionRemovePage'])->name('connectionRemovePage');

// 编辑模式接口 - 改表注释
Route::any('/modifyTable', [ModifyController::class, 'modifyTable'])->name('modifyTable');
// 编辑模式接口 - 改字段注释
Route::any('/modifyColumn', [ModifyController::class, 'modifyColumn'])->name('modifyColumn');
// 编辑模式接口 - 添加 连接
Route::any('/addConnection', [ModifyController::class, 'addConnection'])->name('addConnection');
// 编辑模式接口 - 删除连接
Route::any('/removeConnection', [ModifyController::class, 'removeConnection'])->name('removeConnection');






