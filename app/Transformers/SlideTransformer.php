<?php


namespace App\Transformers;


use App\Models\Order;
use App\Models\Slide;
use App\Models\User;
use League\Fractal\Scope;
use League\Fractal\TransformerAbstract;

class SlideTransformer   extends TransformerAbstract
{
    public function transform(Slide $slide)
    {
        return [
            'id'=>$slide->id,
            'title'=>$slide->title,
            'seq'=>$slide->seq,
            'url'=>$slide->url,
            'status'=>$slide->status,
            'created_at'=>$slide->created_at,

        ];
    }


}
