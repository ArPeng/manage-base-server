<?php
/**
 * Created by PhpStorm.
 * User: peter
 * Date: 2018/5/29
 * Time: 上午10:46
 */

namespace app\admin\controller;


use extend\STATUS_CODE;
use think\Request;

class Group extends Auth
{
    /**
     * @purpose 创建管理组
     * @param Request $request
     * @return array
     * @throws \Exception
     */
    public function create(Request $request)
    {
        $data  = $request->post();
        $group = new \app\model\Group();
        if ((bool)$group->addOne($data)) {
            return result(STATUS_CODE::SUCCESS, '创建成功');
        }
        return result(STATUS_CODE::FAIL, '创建失败');
    }

    /**
     * @purpose 更新管理组
     * @param Request $request
     * @return array
     * @throws \Exception
     */
    public function update(Request $request)
    {
        $data   = $request->post();
        $group  = new \app\model\Group();
        $result = $group->updateById($data);
        if ((bool)$result) {
            return result(STATUS_CODE::SUCCESS, '修改成功');
        }
        return result(STATUS_CODE::FAIL, '修改失败');
    }

    /**
     * @purpose 删除管理组
     * @param Request $request
     * @return array
     */
    public function delete(Request $request)
    {
        $id     = $request->post('id');
        $group  = new \app\model\Group();
        $result = (bool)$group->deleteById($id);
        if ($result) {
            return result(STATUS_CODE::SUCCESS, '删除成功');
        }
        return result(STATUS_CODE::FAIL, '删除失败');
    }

    /**
     * @purpose 通过ID获取单条管理组
     * @param Request $request
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getGroupInfoById(Request $request)
    {
        $id     = $request->get('id');
        $fields = 'id,name,rules,descriptions,create_at,update_at';
        $group  = new \app\model\Group();
        $result = $group->getOneById($id, $fields);
        return result(STATUS_CODE::SUCCESS, $result);
    }

    /**
     * @purpose 获取管理组列表
     * @param Request $request
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getList(Request $request)
    {
        $page            = $request->get('page');
        $size            = $request->get('size');
        $group           = new \app\model\Group();
        $result          = [];
        $result['list']  = $group->getItem($page, $size);
        $result['total'] = $group->total();
        return result(STATUS_CODE::SUCCESS, $result);
    }

    /**
     * @purpose 给当前管理组授权
     * @param Request $request
     * @return array
     * @throws \Exception
     */
    public function authorization(Request $request)
    {
        $id    = $request->post('id');
        $rules = $request->post('rules');
        $group = new \app\model\Group();
        if ($group->updateRulesById($id, $rules)) {
            return result();
        } else {
            return result(
                STATUS_CODE::UPDATE_FAIL,
                '授权失败'
            );
        }
    }

    /**
     * @purpose 获取所有管理组
     * @param Request $request
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function all(Request $request)
    {
        $group  = new \app\model\Group();
        $result = $group->allGroup('id,name,rules');
        return result($result);
    }
}