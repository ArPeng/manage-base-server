<?php
/**
 * Created by PhpStorm.
 * User: peter
 * Date: 2018/5/29
 * Time: 上午10:31
 */

namespace app\admin\controller;


use extend\STATUS_CODE;
use think\Request;

class Rule extends Auth
{
    /**
     * @purpose 创建权限
     * @param Request $request
     * @return array
     * @throws \Exception
     */
    public function create(Request $request)
    {
        $data  = $request->post();
        $model = new \app\model\Rule();
        if ($model->addOnce($data)) {
            return result(
                STATUS_CODE::SUCCESS,
                '添加成功');
        }
        return result(
            STATUS_CODE::CREATE_FAIL,
            '数据创建失败');
    }

    /**
     * @purpose 更新权限
     * @param Request $request
     * @return array
     * @throws \Exception
     */
    public function update(Request $request)
    {
        $data   = $request->post();
        $model  = new \app\model\Rule();
        $result = $model->updateById($data);
        if ($result) {
            return result(
                STATUS_CODE::SUCCESS,
                '修改成功');
        }
        return result(
            STATUS_CODE::UPDATE_FAIL,
            '修改失败');
    }

    /**
     * @purpose 删除权限
     * @param Request $request
     * @return array
     */
    public function delete(Request $request)
    {
        $id     = (int)$request->post('id');
        $model  = new \app\model\Rule();
        $result = $model->deleteById($id);
        if ($result) {
            return result(
                STATUS_CODE::SUCCESS,
                '删除成功');
        }
        return result(
            STATUS_CODE::UPDATE_FAIL,
            '删除失败');
    }

    /**
     * @purpose 通过pid获取权限
     * @param Request $request
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getListByPid(Request $request)
    {
        $pid    = $request->get('pid');
        $model  = new \app\model\Rule();
        $result = $model->getListByPid((int)$pid);
        return result(
            STATUS_CODE::SUCCESS,
            $result);
    }

    /**
     * @purpose 获取无限极数据结构
     * @param Request $request
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function infinite(Request $request)
    {
        // TODO: Implement infinite() method.
        $model = new \app\model\Rule();
        $data  = $model->getAll('id,pid,name,type');
        $_data = [];
        foreach ($data as $item) {
            $_data[$item['id']]['id']   = $item['id'];
            $_data[$item['id']]['pid']  = $item['pid'];
            $_data[$item['id']]['name'] = $item['name'];
            $_data[$item['id']]['type'] = $item['type'];
        }
        $data = generate_tree($_data, 'children');
        return result(STATUS_CODE::SUCCESS, $data);
    }

    /**
     * @purpose 通过ID获取单条权限数据
     * @param Request $request
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getRuleInfoById(Request $request)
    {
        $id     = $request->get('id');
        $fields = 'id,pid,name,identification,icon_class,icon_family,type,address';
        $model  = new \app\model\Rule();
        $result = $model
            ->getOneById($id, $fields);
        if ($result) {
            return result(
                STATUS_CODE::SUCCESS,
                $result);
        }
        return result(
            STATUS_CODE::DATA_NOT_FIND,
            '没有找到需要的数据');
    }

    /**
     * @purpose 获取dashboard 菜单(一级菜单)
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function dashboard ()
    {
        $rule  = new \app\model\Rule();
        $ruleIds    = $rule->getRulesByUid(self::$user_info['uid']);
        $menu       = $rule->firstMenu($ruleIds);
        // 刚写到这里,准备通过ID查询一级菜单
        return result(STATUS_CODE::SUCCESS, $menu);
    }
    /**
     * @purpose 获取侧边栏菜单
     * @param Request $request
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function menu(Request $request)
    {
        $rule           = new \app\model\Rule();
        $identification = '';//$request->get('identification');
        $ruleIds     = $rule->getRulesByUid(self::$user_info['uid']);
        $dashboardId = $rule->identificationToId($identification);
        $menu        = $rule->sidebarMenu($ruleIds)->toArray();
        $menu        = $rule->getSubs($menu, $dashboardId['id']);
        $menu        = generate_tree($menu);
        return result(
            STATUS_CODE::SUCCESS,
            $menu);
    }

    /**
     * @purpose 获取按钮以及展示权限
     * @param Request $request
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function identification(Request $request)
    {
        $rule           = new \app\model\Rule();
        $identification = $request->get('identification');
        $ruleIds    = $rule->getRulesByUid(self::$user_info['uid']);
        $parent     = $rule->identificationToId($identification);
        $rules      = $rule->rules($ruleIds, 'id,pid,identification');
        $menu       = $rule->getSubs($rules, $parent['id']);
        $permission = [];
        foreach ($menu as $v) {
            array_push($permission, $v['identification']);
        }
        return result(STATUS_CODE::SUCCESS, $permission);
    }

    /**
     * @purpose 菜单排序
     * @param Request $request
     * @return array
     */
    public function sort (Request $request)
    {
        $data = $request->post();
        if (!$data) {
            return result(
                STATUS_CODE::DATA_NOT_FIND,
                '参数错误'
            );
        }
        $model = new \app\model\Rule();
        $result = $model
            ->isUpdate(true)
            ->saveAll($data);
        if ($result) {
            return result();
        }
        return result(
            STATUS_CODE::UPDATE_FAIL,
            '更新失败'
        );
    }
}