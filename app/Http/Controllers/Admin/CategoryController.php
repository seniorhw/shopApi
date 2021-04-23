<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Models\Category;
use App\Transformers\CategoryTransformer;
use Illuminate\Http\Request;

class CategoryController extends BaseController
{
    /**
     * 分类列表
     */
    public function index(Request $request)
    {
        if ($request->input('type') == 'all'){
            return cache_category_all();

        }else{
            return cache_category();
        }
    }

    /**
     * 添加分类
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'=>'required|min:2|max:16'
        ],[
            'name.required'=>'分类名称不能为空'
        ]);
        //获取pid
        $pid = $request->input('pid',0);
        //获取是几级分类
        $level = $pid == 0 ? 1 : (Category::find($pid)->level + 1 );
        //如果大于3级   就return
        if ($level > 3) return $this->response->errorBadRequest('最大3级分类');
        $inserts = [
            'name'=>$request->input('name'),
            'pid'=>$pid,
            'level'=>$level,
            'group'=>$request->input('group','goods')
        ];
        Category::create($inserts);
        forget_cache_category();
        return $this->response->created();
    }

    /**
     * 分类详情
     */
    public function show(Category $category)
    {
      return $this->response->item($category, new CategoryTransformer());
    }

    /**
     * 更新分类
     */
    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name'=>'required|min:2|max:16'
        ],[
            'name.required'=>'分类名称不能为空'
        ]);
        //获取pid
        $pid = $request->input('pid',0);
        //获取是几级分类
        $level = $pid == 0 ? 1 : (Category::find($pid)->level + 1 );
        //如果大于3级   就return
        if ($level > 3) return $this->response->errorBadRequest('最大3级分类');
        $inserts = [
            'name'=>$request->input('name'),
            'pid'=>$pid,
            'level'=>$level
        ];
        $category->update($inserts);
        forget_cache_category();
        return $this->response->noContent();
    }

    /**
     *更改分类的状态
     */
    public function status(Category $category)
    {
        $category->status = $category->status == 0 ? 1 : 0;
        $category->save();
        forget_cache_category();
        $this->response->noContent();
    }
}
