<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Mail\OrderePostemail;
use App\Models\Order;
use App\Models\Slide;
use App\Transformers\OrderTransformer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class OrderController extends BaseController
{
    /**
     * 订单列表
     */
    public function index(Request $request)
    {
        //查询条件
        $order_no = $request->input('order_no');
        $trade_no = $request->input('trade_no');
        $status = $request->input('status');
        $orders = Order::when($order_no,function ($q) use ($order_no){
            $q->where('order_no',$order_no);
        })->when($trade_no,function ($q) use ($trade_no){
            $q->where('trade_no',$trade_no);
        })->when($status,function ($q) use ($status){
                $q->where('status',$status);
        })->paginate();
        return $this->response->paginator($orders,new OrderTransformer());
    }
    /**
     * 订单详情
     */
    public function show(Order $order)
    {
        return $this->response->item($order,new OrderTransformer());
    }
    /**
     * 订单发货
     */
    public function post(Order $order,Request $request)
    {
        $request->validate([
            'express_type'=>'required|in:SF,ST,YD',
            'express_no'=>'required'
        ],[
            'express_type.required'=>'快递名称 不能为空',
            'express_type.in'=>'快递名称 只能为SF,ST,YD',
            'express_no.required'=>'快递单号 不能为空',
        ]);

        $order->express_type = $request->express_type;
        $order->express_no = $request->express_no;
        $order->status = 3;
        $order->save();
        //发生邮件
        Mail::to($order->user->email)->queue(new OrderePostemail($order));
        return $this->response->noContent();

    }


    public function status(Slide $slide)
    {
        $slide->status = $slide->status = 0 ? 1 : 0;
        $slide->save();
        return $this->response->noContent();
    }
}
