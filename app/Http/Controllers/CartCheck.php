<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Illuminate\Http\Request;
class CartCheck extends Controller
{
	
	//更改选中
	public function check(Request $request)
	{

		$cartid = $request->cart_id;
	
		        $user_id = auth('api')->id();
 $cart = Cart::where('user_id',$user_id)->whereNotIn('id',$cartid)->get();
   foreach ($cart as $item){
	               $item->is_checked = 0;
		                   $item->save();
   }
		    $cart1 =  Cart::whereIn('id',$cartid)->get();
		        foreach ($cart1 as $item){
				            $item->is_checked = 1;
					                $item->save();
					            }
	        // Cart::where('id',$cartid)->update(['is_checked'=>$c]);

				      }
}
