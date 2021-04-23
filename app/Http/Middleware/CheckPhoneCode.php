<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckPhoneCode
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {

        $request->validate([
            'phone'=>'required|regex:/^1[3-9]\d{9}$/',
            'code'=>'required'
        ],[
            'phone.required'=>'手机号不能为空',
            'phone.regex'=>'请输入正确的手机号',
            'code.required'=>'验证码不能为空'
        ]);

        if (cache($request->input('phone')) != $request->input('code')){
          abort('404','验证码不正确');
        }

        return $next($request);
    }
}
