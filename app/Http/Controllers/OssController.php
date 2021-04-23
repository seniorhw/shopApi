<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\BaseController;
class OssController extends BaseController
{
    public function token()
    {
        $disk = Storage::disk('oss');
        $config = $disk->signatureConfig($prefix = '/', $callBackUrl = '', $customData = [], $expire = 30);
        $configArr = json_decode($config,true);
        return $this->response->array($configArr);
    }


    public function img(Request $request)
    {
        $user = auth()->user();
        $user->imgname = $request->name;
        $user->save();
        return $this->response->noContent();
    }
}
