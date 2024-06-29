<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class TouchDb extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'touch_db';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '建立sqlite数据库';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        if (  is_file( database_path('database.sqlite') ) ){
            return;
        }

        $file = database_path('database.sqlite'); // 指定文件名

// 打开文件，如果文件不存在，将创建它
        $handle = fopen($file, 'w'); // 'w' 表示写入模式

// 检查文件是否成功打开
        if ($handle === false) {
            die('无法创建或打开文件');
        }
        fclose($handle);

        $this->info("sqlite数据库已经创建成功");
        $exitCode = Artisan::call('migrate', [  ] );

        return 0;
    }
}
