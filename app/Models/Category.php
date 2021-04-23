<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    //可以批量赋值的字段
    protected $fillable = ['name','pid','level','group'];

    //模型关联  与自己的模型关联
    public function childs()
    {
        return $this->hasMany(Category::class,'pid','id');
    }

}
