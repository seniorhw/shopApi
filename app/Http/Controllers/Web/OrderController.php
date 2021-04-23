<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\BaseController;
use App\Models\Address;
use App\Models\Cart;
use App\Models\Good;
use App\Models\Order;
use App\Transformers\OrderTransformer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends BaseController
{
    //订单列表

    function index(Request $request)
    {
        $status = $request->input('status');
        $title = $request->input('title');
        $orders = Order::where('user_id',auth('api')->id())
            ->when($status,function ($q) use ($status){
                $q->where('status',$status);
            })
            ->when($title,function ($q) use ($title){
                $q->whereHas('goods',function ($q) use ($title){
                    $q->where('title', 'like', "%{$title}%");
                });
            })
            ->paginate(6);

        return $this->response->paginator($orders ,new OrderTransformer());
    }


    function preview()
    {
        //地址数据
        $address =Address::where('user_id',auth('api')->id())
            ->orderBy('is_default','desc')
            ->get();

        //购物车数据
        $carts = Cart::where('user_id',auth('api')->id())
                        ->where('is_checked',1)
                        ->with('goods')
                        ->get();
        return $this->response->array([
            'address'=>$address,
            'carts'=>$carts
        ]);
    }

    //创建订单
    function store(Request $request)
    {
        $request->validate([
            'address_id'=>'required|exists:addresses,id' //
        ],[
            'address_id.required'=>'收货地址不能为空'
        ]);



        //处理插入的数据
        $user_id = auth('api')->id();
        $order_no = date('YmdHis').rand(0,999999);
        //总金额
        $amount = 0;
        $carts =Cart::where('user_id',$user_id)
            ->where('is_checked',1)
            ->with('goods:id,price,title,stock')
            ->get();
        //订单详情
        $insertData = [];
        foreach ($carts as $key => $cart){
            //如果商品库存不足  提示用户
            if ($cart->goods->stock < $cart->num){
                $this->response->errorBadRequest($cart->goods->title. 'id->'.$cart->goods->id.'库存不足');
            }

            $insertData[] = [
                'goods_id'=>$cart->goods_id,
                'price'=>$cart->goods->price,
                'num'=>$cart->num
            ];
            $amount+=$cart->goods->price * $cart->num;
        }

        try
        {
            DB::beginTransaction();
            //生成订单
            $order= Order::create([
                'user_id'=>$user_id,
                'address_id'=>$request->input('address_id'),
                'amount'=>$amount,
                'order_no'=>$order_no
            ]);

            //插入订单详情表
            $order->Details()->createMany($insertData);

            //删除购物车的商品
            Cart::where('user_id',$user_id)
                ->where('is_checked',1)
                ->delete();

            //减去库存
            foreach ($insertData as $v){
                Good::where('id',$v['goods_id'])->decrement('stock',$v['num']);
            }
	    DB::commit();
	    return $this->response->array(
		    ['id'=>$order->id,'status'=>'204']
	    
	    );
        }catch (\Exception $e){
            DB::rollBack();
            throw $e;
        }
    }


    //查询物流
    public function express(Order $order)
    {
        if (!in_array($order->status, [3,4])) {
            return $this->response->errorBadRequest('订单状态异常');
        }
    }


    //确认收获
    public function confirm(Order $order)
    {
        if ($order->status !=3 ){
            return $this->response->errorBadRequest('订单状态异常');
        }


        try {
            DB::beginTransaction();

            $order->status = 4;
            $order->save();
            $orderDetails = $order->details;



            // 增加订单下所有商品的销量
            foreach ($orderDetails as $orderDetail) {
                // 更新商品的销量
                Good::where('id', $orderDetail->goods_id)->increment('sales', $orderDetail->num);
            }
            DB::commit();
        }catch (\Exception $e){
            throw $e;
            DB::rollBack();
        }

        return $this->response->noContent();
    }


    /**
     *      * 订单详情
     *           */
    public function show(Order $order)
	        {
			        return $this->response->item($order, new OrderTransformer());
    }
}
