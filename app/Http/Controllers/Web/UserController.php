<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\BaseController;
use App\Transformers\UserTransformer;
use Illuminate\Http\Request;

class UserController extends BaseController
{

    //用户个人信息详情
    protected function userinfo()
    {
        return $this->response->item(auth('api')->user() ,new UserTransformer());
    }

    //修改个人信息
    function updateUserinfo(Request $request)
    {
        $request->validate([
            'name'=>'required|max:25'
        ],[
            'name.required'=>'用户名不能为空',
            'name.max'=>'最多25个字符'
        ]);
        $user  = auth('api')->user();
        $user->name = $request->input('name');
        $user->save();
        return $this->response->noContent();
    }

    //修改头像
    function updateAvatar(Request $request)
    {
        $request->validate([
            'avatar'=>'required|max:25'
        ],[
            'avatar.required'=>'用户名不能为空',
            'avatar.max'=>'最多25个字符'
        ]);
        $user  = auth('api')->user();
        $user->avatar = $request->input('avatar');
        $user->save();
        return $this->response->noContent();
    }
}
