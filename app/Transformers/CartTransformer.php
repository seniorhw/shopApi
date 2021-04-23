<?php


namespace App\Transformers;


use App\Models\Cart;
use League\Fractal\TransformerAbstract;

class CartTransformer   extends TransformerAbstract
{

    protected $availableIncludes = ['goods'];
    public function transform(Cart $cart)
    {
        return [
            'id'=>$cart->id,
            'user_id'=>$cart->user_id,
            'goods_id'=>$cart->goods_id,
            'num'=>$cart->num,
            'is_checked'=>$cart->is_checked,
        ];
    }

    public function includeGoods(Cart $cart)
    {
        return $this->item($cart->Goods,new GoodTransformer());
    }


}
