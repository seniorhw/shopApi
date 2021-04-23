<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Http\Requests\Web\AddressRequest;
use App\Models\Address;
use App\Transformers\AddressTransformer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AddressController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $address = Address::where('user_id',auth('api')->id())->get();
        return $this->response->collection($address,new AddressTransformer());
    }

    /**
     * Store a newly created resource in storage.
     *
     */
    public function store(AddressRequest $request)
    {
        $request->offsetSet('user_id',auth('api')->id());
        Address::create($request->all());
        return $this->response->created();
    }

    /**
     *地址详情
     */
    public function show(Address $address)
    {
        return $this->response->item($address,new AddressTransformer());
    }

    /**
     * 更新地址
     */
    public function update(AddressRequest $request, Address $address)
    {
        $address->update($request->all());
        return $this->response->noContent();
    }

    /**
     * 删除地址
     */
    public function destroy(Address $address)
    {
        $address->delete();
        return $this->response->noContent();
    }

    /**
     * 设为默认地址
     */
    public function default(Address $address)
    {
        //如果当前传入的地址已经是默认地址  就返回
        if ($address->is_default == 1){
            return $this->response->errorBadRequest('已经是默认地址 不能重复设置');
        }
        try {
            DB::beginTransaction();

            //查询当前表有没有默认地址
            $is_default = Address::where('user_id',auth('api')->id())
                ->where('is_default',1)
                ->first();
            //如果有默认地址  设置is_default字段为0
            if (!empty($is_default)){
                $is_default->is_default = 0;
                $is_default->save();
            }
            //设置当前地址为默认地址
            $address->is_default = 1;
            $address->save();

            DB::commit();
        }catch (\Exception $e){
            DB::rollBack();
            throw $e;
        }
    }
}
