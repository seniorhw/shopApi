<?php
use App\Models\Category;
if (!function_exists('categoryTrr')){
    function categoryTrr($group = 'goods', $status =false)
    {
        $category = Category::select(['id','name','pid','level','status'])
            ->when($status !== false ,function ($q) use ($status){
                $q->where('status',$status);
            })
            ->where('pid',0)
            ->where('group',$group)
            ->with([
                'childs'=>function($q) use($status){
                    $q->select(['id','name','pid','level','status'])
                        ->when($status !== false ,function ($q) use ($status){
                           $q->where('status',$status);
                        });
                },
                'childs.childs'=>function($q) use ($status){
                    $q->select(['id','name','pid','level','status'])
                        ->when($status !== false ,function ($q) use ($status){
                            $q->where('status',$status);
                        });
                }
            ])
            ->get();

        return $category;

    }
}





/**
 * 缓存没被禁用的分类
 */
if (!function_exists('cache_category')){
    function cache_category()
    {
      return  cache()->rememberForever('cache_category',function (){
            return categoryTrr('goods',1);
        });
    }
}

/**
 * 缓存所有的分类
 *
 */
if (!function_exists('cache_category_all')){
    function cache_category_all()
    {
          return  cache()->rememberForever('cache_category_all',function (){
                return  categoryTrr('goods');
        });

    }
}




/**
 * 缓存没被禁用的菜单
 */
if (!function_exists('cache_category_menu')){
    function cache_category_menu()
    {
       return cache()->rememberForever('cache_category_menu',function (){
            return categoryTrr('menu',1);
        });
    }
}

/**
 * 缓存所有的菜单
 *
 */
if (!function_exists('cache_category_all_menu')){
    function cache_category_all_menu()
    {
        return cache()->rememberForever('cache_category_all_menu',function (){
            return  categoryTrr('menu');
        });

    }
}









/**
 * 清空所有的缓存
 */
if (!function_exists('forget_cache_category')){
    function forget_cache_category()
    {
        //清空分类的缓存
        cache()->forget('cache_category');
        cache()->forget('cache_category_all');
        //清空菜单的缓存
        cache()->forget('cache_category_all_menu');
        cache()->forget('cache_category_menu');
    }
}


//城市相关的缓存
if (!function_exists('city_cache')){
    function city_cache($pid = 0)
    {
        return cache()->rememberForever('city_cache' . $pid , function () use ($pid){
           return \App\Models\City::where('pid',$pid)->get()->keyBy('id');
        });
    }
}




//返回城市的完整名称
if (!function_exists('city_name')){
    function city_name($city_id)
    {
     $city =  \App\Models\City::where('id',$city_id)->with('parent.parent.parent.parent')->first();
     $arr =[
         $city['parent']['parent']['parent']['parent']['name'] ?? '',
         $city['parent']['parent']['parent']['name'] ?? '',
         $city['parent']['parent']['name'] ?? '',
         $city['parent']['name'] ?? '',
     ];

     $str = trim(implode(' ',$arr));
     return $str;
    }
}
