<?php


namespace App\Transformers;


use App\Models\User;
use League\Fractal\Scope;
use League\Fractal\TransformerAbstract;

class UserTransformer   extends TransformerAbstract
{
    public function transform(User $user)
    {
        return [
          'id'=>$user->id,
            'name'=>$user->name,
            'email'=>$user->email,
            'created_at'=>$user->created_at,
            'update_at'=>$user->update_at,
	        'is_locked'=>$user->is_locked,
	        'phone'=>$user->phone,
            'imgname'=>$user->imgname

        ];
    }
}
