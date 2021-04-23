<?php

use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;


$api = app('Dingo\Api\Routing\Router');

$api->version('v1', ['middleware' => 'api.throttle', 'limit' => 60, 'expires' => 1], function ($api) {
        //认证的路由组
    $api->group(['prefix' => 'auth'], function ($api) {
        //用户注册
        $api->post('register', [RegisterController::class, 'store']);
        //用户登录
        $api->post('login', [LoginController::class, 'login']);
        // 通过邮箱获取验证码
        $api->post('reset/password/email/code', [\App\Http\Controllers\Auth\PasswordResetController::class, 'emailCode']);
        // 提交邮箱和验证码, 修改密码
        $api->post('reset/password/email', [\App\Http\Controllers\Auth\PasswordResetController::class, 'resetPasswordByEmail']);



        //需要登录的路由
        $api->group(['middleware'=>'api.auth'],function ($api){
            //退出登录
            $api->post('logout',[LoginController::class,'logout']);
            //刷新token
            $api->post('refresh',[LoginController::class,'refresh']);

            //修改密码
            $api->post('password/update',[\App\Http\Controllers\Auth\PasswordController::class,'updatePassword']);
            //修改邮箱
            $api->post('email',[\App\Http\Controllers\Web\BindController::class,'emailCode']);
            //更新邮箱
            $api->post('email/update',[\App\Http\Controllers\Web\BindController::class,'updateEmail']);


            //修改手机
            $api->post('phone',[\App\Http\Controllers\Web\BindController::class,'phoneCode']);
            //更新手机
            $api->post('phone/update',[\App\Http\Controllers\Web\BindController::class,'updatePhone']);

            //修改个人头像
            $api->patch('user/avatar',[\App\Http\Controllers\Web\UserController::class,'updateAvatar']);

            //上传文件返回token
            $api->post('updatetoken',[\App\Http\Controllers\OssController::class,'token']);
            //上传文件返回的文件名
            $api->post('images',[\App\Http\Controllers\OssController::class,'img']);
        });
    });
});
