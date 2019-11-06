<?php

namespace App\Http\Middleware;

use App\Models\Sys\ErrorModel;
use Closure;
use \Lcobucci\JWT\Parser;
use \Lcobucci\JWT\Signer\Hmac\Sha256;

class jwtCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        $parser = new Parser;
        $signer = new Sha256;
        $secret = config('jwt.JWT_SECRET');

        if($request->hasHeader('Authorization')){
            $token = $request->header('Authorization');
            //解析token
            $parse = $parser->parse($token);
            //验证token合法性
            if (!$parse->verify($signer, $secret)) {
                return response()->json(['code'=>ErrorModel::JWT_ERROR, 'msg'=>'令牌错误！']);
            }

            //验证是否已经过期
            if ($parse->isExpired()) {
                return response()->json(['code'=>ErrorModel::JWT_ERROR, 'msg'=>'令牌过期！']);
            }
        }else{
            return response()->json(['code'=>ErrorModel::JWT_ERROR, 'msg'=>'令牌缺失！']);
        }
        //把token放到参数里面
        request()->offsetSet('token', $token);
        return $next($request);
    }
}
