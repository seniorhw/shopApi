<?php

$api = app('Dingo\Api\Routing\Router');

$params = [
    'middleware'=>
        [
      
            'bindings',//dingoapi 使用模型注入的中间件  在kernel里面配置
            'serializer:array'//去除tranforms响应数据最外层的{}
        ],
    

];
$api->version('v1',$params, function ($api) {


    //首页路由
    $api->get('index',[\App\Http\Controllers\Web\IndexController::class,'index']);
	$api->any('pay/aliyuns',[\App\Http\Controllers\Web\PayController::class,'notify']);
    //商品详情
    $api->get('goods/{goods}',[\App\Http\Controllers\Web\GoodsController::class,'show']);
    //商品列表
    $api->get('goods',[\App\Http\Controllers\Web\GoodsController::class,'index']);
    //需要登录的路由
    $api->group(['middleware'=>'api.auth'],function ($api){

        //个人信息详情
        $api->get('user',[\App\Http\Controllers\Web\UserController::class,'userinfo']);

        //修改个人信息  name
        $api->put('user',[\App\Http\Controllers\Web\UserController::class,'updateUserinfo']);

        //购物车
        $api->resource('cart',\App\Http\Controllers\Web\CartController::class,[
            'except'=>'show'
        ]);
	//购物车是否选
	//购物车是否选中
	        $api->post('cartcheck',[\App\Http\Controllers\CartCheck::class,'check']);
	         
        //订单视图
        $api->get('order/preview',[\App\Http\Controllers\Web\OrderController::class,'preview']);


        //生成订单
        $api->post('orders',[\App\Http\Controllers\Web\OrderController::class,'store']);
        //支付订单
        $api->get('orders/{order}/pay',[\App\Http\Controllers\Web\PayController::class,'pay']);
        // 轮询查询订单状态
        $api->get('orders/{order}/status', [\App\Http\Controllers\Web\PayController::class, 'payStatus']);

        //订单列表
	$api->get('orders',[\App\Http\Controllers\Web\OrderController::class,'index']);
	//订单详情
	$api->get('orders/{order}/show',[\App\Http\Controllers\Web\OrderController::class,'show']);
        //物流查询
        $api->get('orders/{order}/express',[\App\Http\Controllers\Web\OrderController::class,'express']);
        //确认收货
        $api->patch('orders/{order}/confirm',[\App\Http\Controllers\Web\OrderController::class,'confirm']);
        //评论商品
        $api->post('orders/{order}/comment',[\App\Http\Controllers\Web\CommentController::class,'store']);
        //地址
        $api->get('city',[\App\Http\Controllers\Web\CityController::class,'index']);


        //个人地址的路由
        $api->resource('address',\App\Http\Controllers\Web\AddressController::class);
        //设为默认地址的路由
        $api->patch('address/{address}/default',[\App\Http\Controllers\Web\AddressController::class,'default']);
    });
});
