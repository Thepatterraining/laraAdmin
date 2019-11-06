<?php

namespace App\Models\Auth;

use App\Exceptions\CommonException;
use App\Models\BaseModel;
use App\Models\Sys\ErrorModel;
use Illuminate\Support\Facades\DB;

class UserRoleModel extends BaseModel
{
    protected $table = 'auth_user_roles';

    /**
     * 创建用户角色关联
     */
    function add($userid, $roleIds) {
        $roleArr = explode(',', trim($roleIds, ','));
        //查询这些角色在数据库是否存在
        $roles = RoleModel::whereIn('id', $roleArr)->count();
        if ($roles != count($roleArr)) {
            //有角色id不在数据库，返回错误
            DB::rollback();
            throw new CommonException(ErrorModel::ROLE_NOT_FOUND);
        }
        $datas = [];
        $data['user_id'] = $userid;
        foreach($roleArr as $roleid) {
            $data['role_id'] = $roleid;
            $datas[] = $data;
        }
        $this->insert($datas);
    }
}
