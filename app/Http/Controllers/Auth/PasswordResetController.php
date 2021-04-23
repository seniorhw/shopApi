<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\BaseController;
use App\Mail\SeedEmailCode;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class PasswordResetController extends BaseController
{
    public function __construct()
    {
        $this->middleware(['check.email.code'])->only(['resetPasswordByEmail']);
    }
    //发送邮箱验证码
    function emailCode(Request $request)
    {
        $request->validate([
            'email'=>'required|email'
        ],[
            'email.required'=>'邮箱不能为空',
            'email.email'=>'请输入正确的邮箱'
        ]);

        //发送邮件
        Mail::to($request->input('email'))->send(new SeedEmailCode($request->input('email')));
    }

    function resetPasswordByEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users',
            'password' => 'required|min:6|max:16|confirmed'
        ]);
        $user = User::where('email',$request->email)->first();
        $user->password = bcrypt($request->input('password'));
        $user->save();
        return $this->response->noContent();
    }
}
