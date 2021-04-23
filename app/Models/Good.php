<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Good extends Model
{
    use HasFactory;
    //可批量赋值的字段
    protected $fillable =
        ['title','user_id','category_id','description','price','stock','cover','pics','is_on','is_recommend',
        'details'
        ];

    //追加额外的属性
    protected $appends =['pics_url'];

    function getPicsUrlAttribute($key)
    {
        //使用集合处理每一个元素
        return collect($this->pics)->map(function ($item) {
            //return oss_url($item)
        });
    }

    /**
     * 强制转换的属性
     *
     * @var array
     */
    protected $casts = [
        'pics' => 'array',
    ];

    //模型关联分类
    public function category()
    {
        return $this-> belongsTo(Category::class,'category_id','id');
    }

    //模型关联用户
    public function user()
    {
        return $this-> belongsTo(User::class,'user_id','id');
    }

    //模型关联评论
    public function comment()
    {
        return $this->hasMany(Comment::class,'good_id','id');
    }
}
