<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\QueryList\QueryController;
use App\Exceptions\CommonException;
use App\Models\Auth\UserModel;
use App\Models\Auth\UserRoleModel;
use App\Models\Sys\ErrorModel;

class UserController extends QueryController
{
    /**
     * 字典数组
     * ['表里的字段名' => '字典code',...]
     */
    protected $dicArr = [];

    /**
     * 字段映射 可选，不填默认转成下划线格式
     * ['搜索字段' => '表字段',...]
     */
    protected $filedsAdapter = [];

    /**
     * 创建时候的字段映射 可选，不填默认转成下划线格式
     * ['输入字段' => '表字段']
     */
    protected $createAdapter = [];

    //定义表名 格式: table as t
    protected $shortTableName;


    protected function getModel() {
        return new UserModel();
    }

    /*
     * 查询列表
     * @route get.api/lists
     */
    public function getList(Request $request){
        try{
            //检查页码，搜索条件等
            $this->pageValid();

            $list = $this->pageList();
            foreach ($list['data'] as $data) {
                //查询角色
                $data->roleIds = UserRoleModel::whereUserId($data->id)->pluck('role_id');
            }

            //返回数据
            return $this->success($list);
        } catch (Exception $ex) {

        }

    }

    /**
     * 更新
     * @route put.api/info/{id}
     */
    function updateInfo(Request $request, $id, UserRoleModel $userRoleModel) {
        try{
            //查询记录
            $detail = $this->getModel()->find($id);
            if (empty($detail)) {
                //补充错误信息
                throw new CommonException(ErrorModel::USER_NOT_FOUND);
            }
            //更新
            if ($request->has('roleIds')) {
                //选择了角色，插入角色信息
                $userRoleModel->add($id, $request->roleIds);
            }
            $this->update($id,$request->all(),['roleIds']);
            return $this->success(true);
        }catch(Exception $ex) {

        }
    }

    /**
     * 查询一条记录
     * @route get.api/info
     */
    function detail(Request $request) {
        try{
            $rules = [
                'id'=>'required',
            ];
            $messages = [
                'id.required'=>'id为必填项',
            ];
            //验证
            $this->valid($rules, $messages);
            //查询记录
            $detail = $this->getModel()->find($request->id);
            if (empty($detail)) {
                //补充错误信息
                throw new CommonException(ErrorModel::USER_NOT_FOUND);
            }
            return $this->success($detail);
        }catch(Exception $ex) {

        }
    }

    /**
     * 删除一条记录
     * @route delete.api/info/{id}
     */
    function deleteInfo(Request $request, $id) {
        try{
            //查询记录
            $model = $this->getModel();
            $detail = $model->find($id);
            if (empty($detail)) {
                //补充错误信息
                throw new CommonException(ErrorModel::USER_NOT_FOUND);
            }
            //进行删除
            $res = $model->where('id', $id)->delete();
            return $this->success(true);
        }catch(Exception $ex) {

        }
    }

}
