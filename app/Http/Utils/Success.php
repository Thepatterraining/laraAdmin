<?php

namespace App\Http\Utils;

use App\Models\Sys\ErrorModel;

trait Success {

    function success($data = []) {
        $res = ['code'=>'0','msg'=>'请求成功！', 'data'=>$data];
        return response()->json($res);
    }
}
