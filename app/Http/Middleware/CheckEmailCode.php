<?php

namespace App\Http\Middleware;

use App\Http\Controllers\BaseController;
use Closure;
use Illuminate\Http\Request;

class CheckEmailCode extends BaseController
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
            'email'=>'required|email',
            'code'=>'required'
        ],[
            'email.required'=>'邮箱不能为空',
            'email.email'=>'请输入正确的邮箱',
            'code.required'=>'验证码不能为空'
        ]);

        if (cache($request->input('email')) != $request->input('code')){
            return $this->response->errorBadRequest('验证码不正确');
        }
        return $next($request);
    }
}
