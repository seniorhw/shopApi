<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Yansongda\LaravelPay\Facades\Pay;

class PayController extends Controller
{
    //支付
    function pay(Request $request,Order $order){
        $request->validate([
            'type'=>'required|in:aliyun,wechat'
        ],[
            'type.required'=>'支付方式不能为空',
            'type.in'=>'只能选择aliyun wechat两种支付方式'
        ]);
        if ($request->input('type')=='aliyun'){

            $order = [
                'out_trade_no' => $order->order_no,
                'total_amount' => $order->amount,
                'subject' => 'test subject - 测试',
            ];

            return Pay::alipay()->scan($order);

        }

        if ($request->input('type')=='wechat'){

        }
    }


   public function notify()
    {
        $alipay = Pay::alipay();

        try{
            $data = $alipay->verify();



                $order = Order::where('order_no',$data->out_trade_no)->first();



                $order->update([
                    'status' => 2,
                    'pay_time' => $data->gmt_payment,
                    'pay_type' => '支付宝',
                    'trade_no' => $data->trade_no
                ]);


            Log::debug('Alipay notify', $data->all());
        } catch (\Exception $e) {
        }

        return $alipay->success();
    }

    /**
     * 轮询查询订单状态, 看是否支付完成.
     * 注意: 真实项目中, 使用广播系统实现会更好, 也就是通过长连接, 通知客户端, 支付完成
     */
    public function payStatus(Order $order)
    {
        return $order->status;
    }
}
