<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\City;
use Illuminate\Http\Request;

class CityController extends Controller
{
    //
    function index(Request $request)
    {
      return city_cache($request->input('pid',0));
    }
}
