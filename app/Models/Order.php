<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    //批量复制
    protected $fillable =['user_id','address_id','amount','order_no', 'status'];
    //模型所属用户关联
    public function User(){
        return $this->belongsTo(User::class,'user_id','id');
    }


    //订单所属的详情表
    public function Details(){
        return $this->hasMany(OrderDetails::class,'order_id','id');
    }


    /**
     * 订单远程一对多, 关联的商品
     */
    public function goods()
    {
        return $this->hasManyThrough(
            Good::class, // 最终关联的模型
            OrderDetails::class, // 中间模型
            'order_id', // 中间模型和本模型关联的外键
            'id', // 最终关联模型的外键
            'id', // 本模型和中间模型关联的键
            'goods_id' // 中间表和最终模型关联的一个键
        );
    }


}
