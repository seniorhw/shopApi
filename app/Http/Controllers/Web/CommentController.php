<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Good;
use App\Models\Order;
use Illuminate\Http\Request;

class CommentController extends BaseController
{
    public function store(Request $request,Order $order)
    {
        $request->validate([
            'good_id'=>'required',
            'content'=>'required'
        ],[
            'goods_id.required'=>'商品不能为空',
            'content.required'=>'评论不能为空'
        ]);

        //只能确认收货才能评论
        if ($order->status != 4){
            return $this->response->errorBadRequest('订单状态异常');
        }
        //评论的商品要在这个订单里面
        if (!in_array($request->input('good_id'),$order->details()->pluck('goods_id')->toArray())){
            return $this->response->errorBadRequest('此订单不包含此商品');
        }
        //已经评论过的   不能评论
        $comments = Comment::where('user_id',auth('api')->id())
            ->where('good_id',$request->input('good_id'))
            ->where('order_id',$order->id)
            ->count();
        if ($comments>0){
            return $this->response->errorBadRequest('不能再次评论');
        }

        //生成评论的数据
        $request->offsetSet('user_id',auth('api')->id());
        $request->offsetSet('order_id',$order->id);
        $title = Good::where('id',$request->input('good_id'))->first();

            $request->offsetSet('good_title',$title->title);
        Comment::create($request->all());
        return $this->response->created();
    }
}
