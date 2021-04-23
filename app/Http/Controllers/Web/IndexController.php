<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\BaseController;
use App\Models\Category;
use App\Models\Good;
use App\Models\Slide;
use Illuminate\Http\Request;

class IndexController extends BaseController
{

    /**
     * 首页数据  分类 推荐商品  轮播图
     */
    function index()
    {
        //分类
        $categorys = cache_category();
        //轮播
        $slides = Slide::where('status',1)->orderBy('seq')->get();
        //商品
        $goods = Good::where('is_on',1)->where('is_recommend',1)->get();

        return $this->response->array(
            ['categorys' => $categorys,'slides' => $slides,'goods' => $goods]
        );
    }
}
