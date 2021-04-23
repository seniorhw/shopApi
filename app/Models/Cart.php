<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    //批量赋值
    protected $fillable = ['user_id','goods_id','num'];



    /**
     * 与商品表模型关联
     */
    public function Goods()
    {
        return $this->belongsTo(Good::class,'goods_id','id');
    }
}
