<?php
/**
 * Created by PhpStorm.
 * User: peter
 * Date: 2018/5/29
 * Time: 上午11:20
 */

namespace app\admin\controller;


use app\model\Setting;
use extend\STATUS_CODE;
use think\Request;

class Configure extends Auth
{
    /**
     * @purpose 获取权限白名单
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getRuleWhiteList () {
        $model  = new Setting();
        $result = $model->getConfigure('rule_white_list');
        return result(
            STATUS_CODE::SUCCESS,
            $result);
    }

    /**
     * @purpose 设置路由白名单
     * @param Request $request
     * @return array
     * @throws \Exception
     */
    public function setRuleWhiteList (Request $request) {
        $config = $request->post();
        $model  = new Setting();
        $result = $model
            ->setConfigure(
                'rule_white_list',
                $config,
                '路由白名单');
        if ($result) {
            return result(
                STATUS_CODE::SUCCESS,
                'success');
        }
        return result(
            STATUS_CODE::SUCCESS,
            '设置失败!');
    }
}