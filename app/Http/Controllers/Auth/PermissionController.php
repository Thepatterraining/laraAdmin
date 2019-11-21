<?php

namespace App\Http\Controllers\Auth;

use App\Exceptions\CommonException;
use App\Http\Controllers\QueryList\QueryController;
use Illuminate\Http\Request;
use App\Models\Auth\PermissionModel;
use Illuminate\Support\Arr;

class PermissionController extends QueryController
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
        return new PermissionModel;
    }

    /*
     * 查询列表
     * @route get.api/lists
     */
    public function getList(Request $request){
        try{
            $where = [
                'type' => 'menu',
                'parent_id' => 0,
            ];
            $datas = PermissionModel::where($where)->get();
            $datas = $this->transTree($datas);
            return $this->success(['data'=>$datas]);
        } catch (Exception $ex) {

        }

    }

    /**
     * 转换成树形结构
     */
    public function transTree($tree) {
        foreach($tree as $index => $data) {
            //查询它的子菜单
            $childrens = PermissionModel::whereParentId($data->id)->get();
            if (!$childrens->isEmpty()) {
                $data->children = $childrens;
                $this->transTree($childrens);
            }
        }
        return $tree;
    }

    /**
     * 获取菜单的树形结构
     */
    public function getTreeList(Request $request) {
        $datas = PermissionModel::where('type','menu')->get();
        $trees = [];
        foreach($datas as &$data) {
            $trees[intval($data->id)] = ['key' => $data->id, 'id' => $data->id, 'name' => $data->name, 'parentId' => $data->parent_id, 'created_at' => $data->created_at];
        }
        // dd($trees);
        foreach($trees as $tree) {
            // dd($tree);
            $parentIdArr = explode('/',$tree['parentId']);
            $parentId = last($parentIdArr);
            // dd($data);
            $trees[intval($parentId)]['children'][intval($tree['key'])] = &$trees[$tree['key']];
        }
        $res = [];
        foreach ($trees[0]['children'] as $children) {
            $res[] = $children;
        }
        return $this->success($res);
    }

    /**
     * 获取菜单的树形结构
     */
    public function getTree(Request $request) {
        $trees = PermissionModel::where('type','menu')->get();
        $res = [];
        foreach($trees as $tree) {
            $res[] = ['key'=>$tree->id, 'value'=>$tree->id, 'title'=>$tree->name, 'id' => $tree->id, 'pId' => $tree->parent_id];
        }
        return $this->success($res);
    }

    /**
     * 创建
     * @route post.api/info
     */
    function createInfo(Request $request) {
        try{
            //创建
            $this->create($request->all());
            return $this->success(true);
        }catch(Exception $ex) {

        }
    }

    /**
     * 更新
     * @route put.api/info/{id}
     */
    function updateInfo(Request $request, $id) {
        try{
            //查询记录
            $detail = $this->getModel()->find($id);
            if (empty($detail)) {
                //补充错误信息
                throw new CommonException();
            }
            //更新
            $this->update($id,$request->all());
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
                throw new CommonException();
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
                throw new CommonException();
            }
            //进行删除
            $res = $model->where('id', $id)->delete();
            return $this->success(true);
        }catch(Exception $ex) {

        }
    }

}
