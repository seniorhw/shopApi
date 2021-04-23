<?php

namespace App\Console;

use App\Models\Order;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();
        $schedule->call(function (){

        //定时检查订单表  如果超过十分钟 就作废
        $orders = Order::where('status',1)
            ->where('created_at' ,'<', date('Y-m-d H:i:s',time() - 600))
            ->with('Details.good')
            ->get();


        try {
            DB::beginTransaction();
            foreach ($orders as $order){
                $order->status = 5;//作废的字段
                $order->save();


                //还原商品库存
                foreach ($order->Details as $detail){
                    $detail->good->increment('stock',$detail->num);
                }
            }


            DB::commit();
        }catch (\Exception $e){
            DB::rollBack();
            info($e);
        }
        })->everyMinute();

    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
