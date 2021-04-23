<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\BaseController;
use App\Mail\SeedEmailCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Overtrue\EasySms\EasySms;
use Illuminate\Support\Facades\Cache;


class BindController extends BaseController
{
    public function __construct()
    {
        $this->middleware(['check.phone.code'])->only(['updatePhone']);
        $this->middleware(['check.email.code'])->only(['updateEmail']);
    }
    //发送邮箱验证码
    function emailCode(Request $request)
    {
        $request->validate([
            'email'=>'required|email|unique:users'
        ],[
            'email.required'=>'邮箱不能为空',
            'email.email'=>'请输入正确的邮箱',
            'email.unique'=>'邮箱已经存在'
        ]);

        //发送邮件
        Mail::to($request->input('email'))->send(new SeedEmailCode($request->input('email')));
        return $this->response->noContent();
    }
    //更新邮箱
    function updateEmail(Request $request)
    {

        $request->validate([
            'email'=>'unique:users'
        ],[
            'email.unique'=>'邮箱已经存在'
        ]);
            $user = auth('api')->user();
            $user->email = $request->input('email');
            $user->save();
            return  $this->response->noContent();
    }


    //发送手机验证码
    function phoneCode(Request $request)
    {
        $request->validate([
            'phone'=>'required|regex:/^1[3-9]\d{9}$/|unique:users'
        ],[
            'phone.required'=>'手机号不能为空',
            'phone.regex'=>'请输入正确的手机号'
        ]);

        //发送手机验证码

        $config = [
            // HTTP 请求的超时时间（秒）
            'timeout' => 5.0,

            // 默认发送配置
            'default' => [
                // 网关调用策略，默认：顺序调用
                'strategy' => \Overtrue\EasySms\Strategies\OrderStrategy::class,

                // 默认可用的发送网关
                'gateways' => [
                    'smsbao',
                ],
            ],
            // 可用的网关配置
            'gateways' => [
                'errorlog' => [
                    'file' => '/storage/easy-sms.log',
                ],

                'smsbao' => [
                    'user'  => 'a17790636',    //账号
                    'password'   => 'hw123456' ,  //密码
                    'sign_name' => '个人模板',
                ],
            ],
        ];
        $phone = $request->input('phone');
        try {
            $easySms = new EasySms($config);
            $code = rand(10000,99999);

           Cache::put($phone,$code,now()->addMinute(15));
            $easySms->send($phone, [
                'content'  => '【融职六组】您好！您的验证码是'.$code.',在15分钟内有效',
                'template' => '',
                'data' => [
                    'code' => $code
                ],
            ]);
        }catch (\Exception $e){
            return $e->getExceptions();
        }


    }
    //更新手机号
    function updatePhone(Request $request)
    {
        $request->validate([
            'phone'=>'unique:users'
        ],[
            'phone.unique'=>'手机号已经存在'
        ]);

            $user = auth('api')->user();
            $user->phone = $request->input('phone');
            $user->save();
            return  $this->response->noContent();
    }
}
