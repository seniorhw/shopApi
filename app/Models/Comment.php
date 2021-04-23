<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    //排除可以批量赋值的字段
    protected $guarded =[];

    public function User()
    {
        return $this->hasOne(User::class,'id','user_id');
    }


    public function Good()
    {
        return $this->hasOne(Good::class,'id','good_id');
    }
}
