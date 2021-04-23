<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\BaseController;
use App\Models\Cart;
use App\Models\Good;
use App\Transformers\CartTransformer;
use Illuminate\Http\Request;

class CartController extends BaseController
{
    /**
     * 购物车详情
     */
    public function index(Cart $cart)
    {
       $carts =  Cart::where('user_id',auth('api')->id())
           ->get();
       return $this->response->collection($carts,new CartTransformer());
    }

    /**
     *添加购物车
     */
    public function store(Request $request)
    {
        $request->validate([
            'goods_id'=>'required|exists:goods,id',
            'num'=>[
                function ($attribute, $value, $fail) use($request) {
                //查找商品
                   $goods = Good::find($request->input('goods_id'));
                    if ($value > $goods->stock ) {
                        $fail('商品数量超过库存');
                    }
                }
            ]
        ],[
            'goods_id.required'=>'商品id不能为空',
            'goods_id.exists'=>'商品不存在',
        ]);
        //查询购物车是否已经存在商品
        $cart = Cart::where('user_id',auth('api')->id())
            ->where('goods_id',$request->input('goods_id'))
            ->first();
        if (!empty($cart)){
            $cart->num += $request->input('num',1);
            $cart->save();
            return  $this->response->noContent();
        }
        Cart::create([
            'user_id'=>auth('api')->id(),
            'goods_id'=>$request->input('goods_id'),
            'num'=>$request->input('num',1)
        ]);


        return $this->response->created();
    }

	//更改选中
        public function check(Request $request){
              foreach ($request->cart_id as $item){
                         $check[] = Cart::where('id',$item)->get();
                                }
                                       foreach ($check as $value){
                                                  $value->is_checked = 0;
                                                             $value->save();
                                                                    }
         }

    /**
     *更新商品数量
     */
    public function update(Request $request, Cart $cart)
    {
        $request->validate([
            'num'=>[
                'required',
                'gte:1',
                function ($attribute, $value, $fail) use($cart) {
                    if ($value >$cart->goods->stock ) {
                        $fail('商品数量超过库存');
                    }
                }
            ]
        ],[
            'num.required'=>'数量不能为空',
            'num.gte'=>'商品数量要大于1',
        ]);
        $cart->num = $request->input('num');
        $cart->save();
        return $this->response->noContent();

    }

    /**
    * 删除商品
     */
    public function destroy(Cart $cart)
    {
    $cart->delete();
    $this->response->noContent();
    }
}
