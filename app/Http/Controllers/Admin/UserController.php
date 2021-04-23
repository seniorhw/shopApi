<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Models\User;
use App\Transformers\UserTransformer;
use Illuminate\Http\Request;

class UserController extends BaseController
{
    /**
     * 用户列表首页
     */
    public function index(Request $request)
    {
        //处理查询
        $name = $request->query('name');
        $email = $request->query('email');
        $user  = User::when($name,function ($q) use ($name){
            $q->where('name','like',"%$name%");
        })
            ->when($email,function ($q) use ($email){
                $q->where('name','like',"%$email%");
            })
            //分页
            ->paginate(2);
        return $this->response->paginator($user,new UserTransformer());
    }




    /**
     *查看用户详情信息
     */
    public function show(User $user)
    {
        return $this->response->item($user ,new UserTransformer());
    }

    /**
     * 禁用和启用用户
     */
    public function lock(User $user)
    {
        $user->is_locked = $user->is_locked == 1 ? 0 : 1;
        $user->save();
        return $this->response->noContent();

    }
}
