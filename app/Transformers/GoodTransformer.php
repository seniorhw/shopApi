<?php


namespace App\Transformers;


use App\Models\Category;
use App\Models\Good;
use League\Fractal\TransformerAbstract;

class GoodTransformer extends TransformerAbstract
{
    //可以使用的include方法
    protected $availableIncludes = ['category','user','comment'];

    public function transform(Good $good)
    {
        return [
            'id' => $good->id,
            'category_id' => $good->category_id,
            'title' => $good->title,
            'description' => $good->description,
            'price' => $good->price,
            'stock' => $good->stock,
            'cover' => $good->cover,
            'pics' => $good->pics,
            'details' => $good->details,
        ];
    }

    //商品的分类信息
    public function includeCategory(Good $good)
    {
        return $this->item($good->category, new CategoryTransformer());

    }
    //商品的发表用户信息
    public function includeUser(Good $good)
    {
        return $this->item($good->user, new UserTransformer());

    }
    //商品的评论信息
    public function includeComment(Good $good)
    {
        return $this->collection($good->comment,new CommentTransformer());
    }

}
