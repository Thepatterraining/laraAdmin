<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Auth\UserModel;
use App\Models\Auth\UserRoleModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    protected $rules = [
        'name' => 'required|unique:auth_users',
        'email' => 'required|unique:auth_users',
        'password' => 'required',
        'roleIds' => 'nullable'
    ];

    protected $messages = [
        'name.required' => '用户名必填！',
        'name.unique' => '用户已注册！',
        'email.required' => '邮箱必填！',
        'email.unique' => '邮箱已注册！',
        'password.required' => '密码必填！',
    ];

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function index(Request $request, UserModel $userModel, UserRoleModel $userRoleModel)
    {
        $this->valid();

        DB::beginTransaction();
        //创建
        $user = $userModel->add($request->all());

        if ($request->has('roleIds')) {
            //选择了角色，插入角色信息
            $userRoleModel->add($user->id, $request->roleIds);
        }
        DB::commit();

        return $this->success(true);
    }
}
