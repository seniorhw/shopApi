<?php


namespace App\Transformers;


use App\Models\Order;
use App\Models\OrderDetails;
use App\Models\User;
use League\Fractal\Scope;
use League\Fractal\TransformerAbstract;

class OrderDetailsTransformer   extends TransformerAbstract
{
    protected $availableIncludes = ['order','good'];
    public function transform(OrderDetails $orderDetails)
    {
        return [
            'id'=>$orderDetails->id,
            'order_id'=>$orderDetails->order_id,
            'goods_id'=>$orderDetails->goods_id,
            'price'=>$orderDetails->price,
            'num'=>$orderDetails->num,
            'created_at'=>$orderDetails->created_at
        ];
    }

    //订单所属用户信息
    public function includeOrder(OrderDetails $orderDetails)
    {
        return $this->item($orderDetails->order,new OrderTransformer());
    }
    public function includeGood(OrderDetails $orderDetails )
    {
        return $this->item($orderDetails->good,new GoodTransformer());
    }
}
