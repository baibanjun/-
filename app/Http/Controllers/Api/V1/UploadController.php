<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\UploadService;

class UploadController extends Controller
{
   
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function poster(Request $request)
    {
        return UploadService::poster($request->base64_img, $request->user_name);
    }
}
