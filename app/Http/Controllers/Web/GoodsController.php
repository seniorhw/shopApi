<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\BaseController;
use App\Models\Good;
use Illuminate\Http\Request;

class GoodsController extends BaseController
{
    //商品列表
    public function index(Request $request)
    {
        //标题
        $title = $request->input('title');
        //分类
        $category_id = $request->input('category_id');
        //销量
        $sales =  $request->input('sales');
        //价格
        $price =  $request->input('price');
        //评论
        $comment = $request->input('comment');
        //商品列表
        $goods = Good::select('id','title','cover','price','sales')
            ->when($title,function ($q,$title){
                $q->where('title','like',"%{$title}%");
            })
            ->when($category_id,function ($q,$category_id){
                $q->where('category_id',$category_id);
            })
            ->when($sales == 1,function ($q,$sales){
                $q->orderBy('sales','desc');
            })
            ->when($price == 1,function ($q,$price){
                $q->orderBy('price','desc');
            })
            ->withCount('comment')
            ->when($comment == 1,function ($q,$comment){
                $q->orderBy('comment_count','desc');
            })
            ->orderBy('updated_at','desc')
            ->where('is_on',1)
	    ->paginate(6)
            ->appends([
                'title'=>$title,
                'category_id'=>$category_id,
                'sales'=>$sales,
                'price'=>$price,
                'comment'=>$comment,
            ]);
        //推荐商品
        $recommend = Good::select('id','title','cover','price')
            ->where('is_recommend',1)
            ->where('is_on',1)
            ->withCount('comment')
            ->inRandomOrder()
            ->take(10)
            ->get();
        //分类
        $category = cache_category();

        return $this->response->array([
            'goods'=>$goods,
            'goods_recommend'=>'',
            'category'=>$category
        ]);
    }


    /**
     * 商品详情
     */
    function show($id)
    {
        //返回商品
       $goods = Good::where('id',$id)
           ->with([
               'comment.user'=>function($q){
                $q->select('id','name','avatar');
               }
           ])
//           ->append('pics_url');  相当于模型里面追加额外的属性
           ->first();

        //相似商品
        $links = Good::where('category_id',$goods->category_id)
            ->select('id','title','price','cover','sales')
            ->inRandomOrder()
            ->take(10)
            ->get()
            //隐藏返回的字段   makeHidden('字段)  对整个集合的某个字段隐藏
            ->transform(function ($item){
               return $item->setHidden(['is_on']);
            });


       return $this->response->array([
           'goods'=>$goods,
           'links'=>$links
       ]);
    }
}
