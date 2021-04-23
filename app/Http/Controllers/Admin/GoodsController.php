<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Admin\GoodRequest;
use App\Models\Category;
use App\Models\Good;
use App\Transformers\GoodTransformer;
use Illuminate\Http\Request;

class GoodsController extends BaseController
{
    /**
     *商品列表
     */
    public function index(Good $good,Request $request)
    {
        //搜索
        $title = $request->query('title');
        $category = $request->query('category_id');
        $is_on = $request->query('is_on',false);
        $is_recommend = $request->query('is_recommend',false);
        $goods = Good::when($title,function ($q) use ($title){
            $q->where('title','like',"%$title%");
        })->when($category,function ($q) use ($category){
            $q->where('category_id',$category);
        })->when($is_on !== false,function ($q) use ($is_on){
            $q->where('is_on',$is_on);
        })->when($is_recommend !== false,function ($q) use ($is_recommend){
            $q->where('is_recommend',$is_recommend);
        })->paginate(2);
//        $goods = Good::paginate(2);
        return $this->response->paginator($goods,new GoodTransformer());
    }

    /**
     *添加商品
     */
    public function store(GoodRequest $request)
    {
        //检查分类 只能使用三级分类  并且分类不能被禁用
        $category=Category::find($request->category_id);
        if (!$category) return $this->response->errorBadRequest('分类不存在');
        if ($category->level != 3 ) return $this->response->errorBadRequest('只能选择3级分类');
        if ($category->status == 0 ) return $this->response->errorBadRequest('分类被禁');
        $request->offsetSet('user_id',auth('api')->id());
        Good::create($request->all());
        return $this->response->created();
    }

    /**
     * 商品详情
     */
    public function show(Good $good)
    {
        return $this->response->item($good,new GoodTransformer());
    }

    /**
     *更新商品
     */
    public function update(Request $request, Good $good)
    {
        //检查分类 只能使用三级分类  并且分类不能被禁用
        $category=Category::find($request->category_id);
        if (!$category) return $this->response->errorBadRequest('分类不存在');
        if ($category->level != 3 ) return $this->response->errorBadRequest('只能选择3级分类');
        if ($category->status == 0 ) return $this->response->errorBadRequest('分类被禁');
        $good->update($request->all());
        return $this->response->noContent();
    }

    /**
     * 是否上架
     */
    public function is_on(Good $good)
    {
        $good->is_on = $good->is_on == 0 ? 1 : 0;
        $good->save();
        return $this->response->noContent();
    }

    /**
     * 是否推荐
     */
    public function is_recommend(Good $good)
    {
        $good->is_recommend = $good->is_recommend == 0 ? 1 : 0;
        $good->save();
        return $this->response->noContent();
    }
}
