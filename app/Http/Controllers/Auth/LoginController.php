<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Auth\UserModel;
use App\Models\Sys\ErrorModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class LoginController extends Controller
{

    protected $rules = [
        'name' => 'required|exists:auth_users,name',
        'pwd' => 'required',
    ];

    protected $messages = [
        'name.required' => '用户名必填！',
        'name.exists' => '用户不存在！',
        'pwd.required' => '密码必填！',
    ];


    /**
     * 登陆
     */
    public function index(Request $request, UserModel $model) {
        //验证
        $this->valid();

        //查询用户
        $user = UserModel::where('name', $request->name)->first(['name','id','email','password']);

        //判断密码
        if (!$model->checkPwd(strval($user->password), strval($request->pwd))) {
            //没有通过，返回错误
            return $this->error(ErrorModel::PWD_ERROR);
        }

        //登陆
        Redis::setex($request->post('token'), config('jwt.JWT_EXP_TIME'), json_encode($user));

        return $this->success($user);
    }
}
