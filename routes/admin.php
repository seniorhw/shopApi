<?php
use App\Http\Controllers\Admin\UserController;
$api = app('Dingo\Api\Routing\Router');

$params = [
    'middleware'=>
        [
            'api.throttle',
            'bindings',//dingoapi 使用模型注入的中间件  在kernel里面配置
            'serializer:array'//去除tranforms响应数据最外层的{}
        ],
    'limit'=>60,
    'expires'=>1
];
$api->version('v1',$params, function ($api) {

    $api->group(['prefix'=>'admin'],function ($api){
        //需要登录的路由

        $api->group(['middleware'=>['api.auth','checkpermission']],function ($api){

            /**
             * 用户管理路由
             */
            $api->resource('users',UserController::class,[
                'only'=>['index','show']
            ]);

            //用户的禁用和启用
            $api->patch('users/{user}/lock',[UserController::class,'lock'])->name('users.lock');


            /**
             * 分类管理的路由
             *
             */

            $api->resource('category',\App\Http\Controllers\Admin\CategoryController::class,[
                'except'=>['destroy']
            ]);
            /**
             * 分类的启用和禁用
             */
            $api->patch('category/{category}/status',[\App\Http\Controllers\Admin\CategoryController::class,'status'])->name('category.status');

            /**
             * 商品相关路由
             */
            $api->resource('good',\App\Http\Controllers\Admin\GoodsController::class,[
                'except'=>['destroy']
            ]);
            /**
             * 商品是否上架的路由
             */
            $api->patch('good/{good}/on',[\App\Http\Controllers\Admin\GoodsController::class,'is_on'])->name('goods.on');
            /**
             * 商品是否推荐的路由
             */
            $api->patch('good/{good}/recommend',[\App\Http\Controllers\Admin\GoodsController::class,'is_recommend'])->name('goods.recommend');


            /**
             * 评价
             */
            //评价列表
            $api->get('comments',[\App\Http\Controllers\Admin\CommentController::class,'index'])->name('comments.index');
            //评论详情
            $api->get('comments/{comment}',[\App\Http\Controllers\Admin\CommentController::class,'show'])->name('comments.show');
            //回复评论
            $api->patch('comments/{comment}/reply',[\App\Http\Controllers\Admin\CommentController::class,'reply'])->name('comments.reply');


            /**
             * 订单管理
             */
            //订单列表
            $api->get('order',[\App\Http\Controllers\Admin\OrderController::class,'index'])->name('order.index');
            //评论详情
            $api->get('order/{order}',[\App\Http\Controllers\Admin\OrderController::class,'show'])->name('order.show');
            //回复评论
            $api->patch('order/{order}/post',[\App\Http\Controllers\Admin\OrderController::class,'post'])->name('order.post');


            /**
             * 轮播图管理
             */
            $api->resource('slide',\App\Http\Controllers\Admin\SlideController::class);
            //轮播排序
            $api->patch('slide/{slide}/seq',[\App\Http\Controllers\Admin\SlideController::class,'seq'])->name('slide.seq');
            //轮播的禁用和启用
            $api->patch('slide/{slide}/status', [\App\Http\Controllers\Admin\SlideController::class, 'status']);
            /**
             * 菜单管理
             */
            $api->get('menus',[\App\Http\Controllers\Admin\MenuController::class,'index'])->name('menus.index');


        });
    });

});
