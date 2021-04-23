<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;

class MenuController extends BaseController
{
    /**
     * 菜单列表
     */
    public function index(Request $request)
    {
        if ($request->input('type') == 'all'){
            return cache_category_all_menu();

        }else{
            return cache_category_menu();
        }
    }

}
