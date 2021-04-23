<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory;


    //指定模型关联的表名
    protected $table = 'city';


    //自己的子集
    public function children()
    {
        return $this->hasMany(City::class,'pid','id');
    }

    //自己的父集
    public function parent()
    {
        return $this->belongsTo(City::class,'pid','id');
    }
}
