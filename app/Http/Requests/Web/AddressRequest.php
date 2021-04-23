<?php

namespace App\Http\Requests\Web;

use App\Models\City;
use Illuminate\Foundation\Http\FormRequest;

class AddressRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
		'name'=>'required',
		 'city'=>'required',
		             'province'=>'required',
			                 'county'=>'required',
            'address'=>'required',
            'phone'=>'required|regex:/^1[3-9]\d{9}$/',

        ];
    }

    public function messages()
    {
        return [
          'name.required'=>'收货姓名不能为空',
	  'address.required'=>'详细地址不能为空',
	  'city.required'=>'城市不能为空',
	            'county'=>'区县不能为空',
		              'province'=>'省份不能为空',
          'phone.required'=>'手机号不能为空'  ,
          'phone.regex'=>'手机号不正确'
        ];
    }
}
