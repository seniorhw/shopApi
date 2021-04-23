<?php


namespace App\Transformers;


use App\Models\Address;
use App\Models\Cart;
use League\Fractal\TransformerAbstract;

class AddressTransformer   extends TransformerAbstract
{

    public function transform(Address $address)
    {
	    return [
		    'id'=>$address->id,
		                'name'=>$address->name,
				'city'=>$address->city,
				'is_default'=>$address->is_default,
					                'province'=>$address->province,
							            'county'=>$address->county,
								                'address'=>$address->address,
										            'phone'=>$address->phone,
											    'created_at'=>$address->created_at,
											    'addcode'=>$address->addcode,
													            'updated_at'=>$address->updated_at,
        ];
    }




}
