<?php

namespace App\Models\Sys;

use App\Models\BaseModel;

class ErrorModel extends BaseModel
{
    protected $table = 'sys_errors';

    const PWD_ERROR = '100001'; //密码错误！
    const ROLE_NOT_FOUND = '100002'; //角色未定义！


    const JWT_ERROR = '400003';  //jwt错误

}
