<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PasswordController extends BaseController
{
    function updatePassword(Request $request)
    {

        $request->validate([
            'old_password'=>'required|max:16|min:4',
            'new_password'=>'required|max:16|min:4|confirmed'
        ],
            [
             'old_password.required'=>'旧密码不能为空',
             'old_password.max'=>'旧密码最大16个字符',
             'old_password.min'=>'旧密码最小4个字符',
             'new_password.required'=>'新密码不能为空',
             'new_password.max'=>'新密码最大16个字符',
             'new_password.min'=>'新密码最小4个字符',
             'new_password.confirmed'=>'两次密码不一致',
            ]);

        if (!password_verify($request->input('old_password'),auth('api')->user()->password)){
            return $this->response->errorBadRequest('旧密码错误');
        }else{
           $user = auth('api')->user();
            $user->password = bcrypt($request->input('new_password'));
            $user->save();
            return  $this->response->noContent();
        }
    }
}
