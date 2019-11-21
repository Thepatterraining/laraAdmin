<?php

namespace App\Models\Auth;

use App\Models\BaseModel;
use Illuminate\Support\Facades\Hash;

class UserModel extends BaseModel
{

    protected $table = 'auth_users';

    /**
     * 检查用户密码是否正确
     * @param string $pwd 用户加密后的密码
     * @param string $confirmPwd 用户输入的密码
     * @return bool
     */
    function checkPwd(string $pwd, string $confirmPwd):bool {

        if (Hash::check($confirmPwd, $pwd)) {
            return true;
        }
        return false;
    }

    /**
     * 获取加密后的密码
     * @param string $pwd 密码
     * @return string
     */
    function getPwd(string $pwd):string {

        return Hash::make($pwd);
    }

    /**
     * 创建新用户
     */
    function add($data) {
        $this->name = $data['name'];
        $this->email = $data['email'];
        $this->password = $this->getPwd($data['password']);
        $this->save();

        return $this;
    }
}
