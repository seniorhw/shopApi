<?php


namespace App\Transformers;


use App\Models\Order;
use App\Models\User;
use League\Fractal\Scope;
use League\Fractal\TransformerAbstract;

class OrderTransformer   extends TransformerAbstract
{
    protected $availableIncludes = ['user','details'];
    public function transform(Order $order)
    {
        return [
            'id'=>$order->id,
            'amount'=>$order->amount,
            'status'=>$order->status,
            'address_id'=>$order->address_id,
            'express_type'=>$order->express_type,
            'pay_time'=>$order->pay_time,
            'pay_type'=>$order->pay_type,
            'trade_no'=>$order->trade_no,
            'order_no'=>$order->order_no,
            'created_at'=>$order->created_at
        ];
    }

    //订单所属用户信息
    public function includeUser(Order $order)
    {
        return $this->item($order->user,new UserTransformer());
    }
    public function includeDetails(Order $order)
    {
        return $this->collection($order->Details ,new OrderDetailsTransformer());
    }
}
