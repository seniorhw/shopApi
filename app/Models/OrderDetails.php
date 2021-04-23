<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetails extends Model
{
    use HasFactory;

    //批量复制的字段
    protected $fillable = ['num','goods_id','price','order_id',''];
    /**
     * 订单商品详情所属的主表
     */
    public function order()
    {
        return $this->belongsTo(Order::class,'order_id','id');
    }

    /**
     * 订单商品详情所有的商品
     */
    public function good()
    {
        return $this->belongsTo(Good::class,'goods_id','id');
    }
}
