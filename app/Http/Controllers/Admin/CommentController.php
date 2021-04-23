<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Models\Comment;
use App\Models\Good;
use App\Transformers\CommentTransformer;
use Illuminate\Http\Request;

class CommentController extends BaseController
{
    /**
    * 评论列表
     */
    public function index(Request $request)
    {
        //搜索的条件
        //商品的评价  好评中评差评
        $rate = $request->query('rate');
        //商品的标题
        $good_title = $request->query('good_title');
        $comments =  Comment::when($rate,function ($q) use ($rate){
          $q->where('rate',$rate);
        })->when($good_title,function ($q) use ($good_title){
            $goods = Good::where('title','like',"%{$good_title}%")->pluck('id');
            $q->whereIn('good_id',$goods);
        })


        ->paginate(2);
        return $this->response->paginator($comments, new CommentTransformer());
    }

    /**
     * 显示评论详情
     */
    public function show(Comment $comment)
    {
        return $this->response->item($comment,new CommentTransformer());
    }

    /**
     * 回复
     */
    public function reply(Request $request,Comment $comment)
    {
        $request->validate([
            'reply' => 'required|max:255'
        ],[
            'reply.required' => '回复 不能为空',
            'reply.max' => '回复 最多255字符',
        ]);
        $comment->reply = $request->reply;
        $comment->save();
        return $this->response->noContent();
    }



}
