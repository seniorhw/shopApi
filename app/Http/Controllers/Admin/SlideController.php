<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Models\Slide;
use App\Transformers\SlideTransformer;
use Illuminate\Http\Request;

class SlideController extends BaseController
{
    /**
     *轮播图列表
     */
    public function index()
    {
        $slides = Slide::paginate(2);
        return $this->response->paginator($slides, new SlideTransformer());
    }

    /**
     * 添加轮播图
     */
    public function store(Request $request)
    {
        $request->validate([
            'title'=>'required',
            'img'=>'required',
        ],[
            'title.required'=>'标题不能为空',
            'img.required'=>'图片地址不能为空',
        ]);

        //查询最大的seq
        $max_seq = Slide::max('seq') ?? 0;
        $max_seq++;
        $request->offsetSet('seq',$max_seq);
        Slide::create($request->all());
    }

    /**
     *轮播图详情
     */
    public function show(Slide $slide)
    {
        return $this->response->item($slide ,new SlideTransformer());
    }

    /**
        更新轮播图
     */
    public function update(Request $request, Slide $slide)
    {

        $request->validate([
            'title'=>'required',
            'img'=>'required',
        ],[
            'title.required'=>'标题不能为空',
            'img.required'=>'图片地址不能为空',
        ]);

        $max_seq = Slide::max('seq') ?? 0;
        $max_seq++;
        $request->offsetSet('seq',$max_seq);
        $slide->update($request->all());
        return $this->response->noContent();
    }

    /**
     *删除轮播图
     */
    public function destroy(Slide $slide)
    {
        $slide->delete();
        return $this->response->noContent();
    }

    /**
     * 排序
     */
    public function seq(Request $request , Slide $slide)
    {
        $slide->seq = $request->input('seq','1');
        $slide->save();
        return $this->response->noContent();
    }
}
