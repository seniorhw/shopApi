<?php


namespace App\Transformers;


use App\Models\Category;
use App\Models\Comment;
use League\Fractal\TransformerAbstract;

class CommentTransformer   extends TransformerAbstract
{


    protected $availableIncludes = ['user','good'];

    public function transform(Comment $comment)
    {
        $pics_url=[];
        if (is_array($comment->pics)){
            foreach ($comment->pics as $p){
                array_push($pics_url,$p);
            }
        }

        return [
            'id'=>$comment->id,
            'rate'=>$comment->rate,
            'reply'=>$comment->replay,
            'pics'=>$comment->pics,
            'created_at'=>$comment->created_at,
            'updated_at'=>$comment->updated_at,
            'content'=>$comment->content,
        ];
    }

    public function includeUser(Comment $comment)
    {
        return $this->item($comment->user,new UserTransformer());
    }

    public function includeGood(Comment $comment)
    {
        return $this->item($comment->good,new GoodTransformer());
    }

}
