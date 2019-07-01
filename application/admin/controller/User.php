<?php
/**
 * Created by PhpStorm.
 * User: peter
 * Date: 2018/5/29
 * Time: 上午9:23
 */

namespace app\admin\controller;

use app\model\Administrator;
use app\model\Authorization;
use extend\STATUS_CODE;
use think\Request;

class User extends Auth
{
    /**
     * @purpose 创建管理员
     * @param Request $request
     * @return array
     */
    public function create(Request $request)
    {
        $data  = $request->post();
        $model = new Administrator();
        if (empty($data)) {
            return result(
                STATUS_CODE::PARAMETER_ERROR,
                '参数不能为空');
        }

        if (empty($data['username'])) {
            return result(
                STATUS_CODE::PARAMETER_ERROR,
                '缺少用户名');
        }

        if (!preg_match('/^[a-zA-Z0-9-_]{6,}$/',
            $data['username'])) {
            return result(
                STATUS_CODE::PARAMETER_ERROR,
                '用户名格式错误,6位以上数字与字母的组合!'
            );
        }

        if (!empty($data['mobile']) &&
            !check_mobile($data['mobile'])) {
            return result(
                STATUS_CODE::PARAMETER_ERROR,
                '手机号码格式错误');
        }

        if (!empty($data['email']) &&
            !check_email($data['email'])) {
            return result(
                STATUS_CODE::PARAMETER_ERROR,
                '邮箱格式错误');
        }
        if (empty($data['password'])) {
            return result(
                STATUS_CODE::PARAMETER_ERROR,
                '密码参数不能为空');
        }
        if (!preg_match('/^[a-zA-Z\d_]{8,}$/',
            $data['password'])) {
            return result(
                STATUS_CODE::PARAMETER_ERROR,
                '密码格式错误');
        }

        // 检测用户名是否已存在
        if ($model->total(['username' => $data['username']]) > 0) {
            return result(
                STATUS_CODE::PARAMETER_ERROR,
                '用户名已存在!'
            );
        }
        /**
         * 对密码进行加密
         */
        $password          = password(strtolower(trim($data['password'])));
        $_data['mobile']   = isset($data['mobile']) ? $data['mobile'] : '';
        $_data['email']    = isset($data['email']) ? $data['email'] : '';
        $_data['name']     = $data['name'];
        $_data['username'] = $data['username'];
        $_data             = array_merge($_data, $password);

        // 生成uuid
        $uuid = getUUID();
        RE_UUID:
        $uuid = getUUID();
        // 检测uuid是否存在
        $count = $model
            ->where(
                'uuid',
                $uuid
            )->count();
        if ($count > 0) {
            goto RE_UUID;
        }
        $_data['uuid'] = $uuid;
        // 检测邮箱是否存在
        if ($_data['email']) {
            $count = $model
                ->where(
                    'email',
                    $_data['email']
                )->count();
            if ($count > 0) {
                return result(
                    STATUS_CODE::PARAMETER_ERROR,
                    '邮箱已存在');
            }

        }
        // TODO 需要检测手机号码或者邮箱是否存在
        if ($_data['mobile']) {
            $count = $model
                ->where(
                    'mobile',
                    $_data['mobile']
                )->count();
            if ($count > 0) {
                return result(
                    STATUS_CODE::PARAMETER_ERROR,
                    '手机号码已存在');
            }

        }
        $result = $model
            ->data($_data)
            ->save();
        if (!$result) {
            return result(
                STATUS_CODE::PARAMETER_ERROR,
                '创建失败');
        }
        return result(
            STATUS_CODE::SUCCESS,
            ['uuid' => $model->uuid],
            '创建成功');
    }

    /**
     * @purpose 获取管理员列表
     * @param Request $request
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function items(Request $request)
    {
        $page   = $request->get('page', 1);
        $size   = $request->get('num', 20);
        $model  = new Administrator();
        $fields = 'uuid,mobile,username,email,name,avatar,status';
        $where  = [];
        $total  = $model
            ->total($where);
        $result = $model
            ->getItem(
                (int)$page,
                (int)$size,
                $where,
                'create_at desc',
                $fields);
        return result(
            STATUS_CODE::SUCCESS,
            [
                'list'  => $result,
                'total' => $total
            ]
        );
    }

    /**
     * @purpose 获取管理员信息
     * @param Request $request
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function info(Request $request)
    {
        $uuid   = $request->get('uuid');
        $model  = new Administrator();
        $fields = 'name,mobile,username,email,status';
        $result = $model
            ->getUserInfoByUUID($uuid, $fields);
        if ($result) {
            return result(STATUS_CODE::SUCCESS, $result);
        }
        return result(
            STATUS_CODE::DATA_NOT_FIND,
            '没有找到数据!');
    }

    /**
     * @purpose 更新管理员信息
     * @param Request $request
     * @return array
     */
    public function update(Request $request)
    {
        $uuid  = $request->post('uuid');
        $data  = $request->post('data');
        $model = new Administrator();
        if (empty($uuid)) {
            return result(
                STATUS_CODE::PARAMETER_ERROR,
                '缺少参数[UUID]');
        }
        if (empty($data)) {
            return result(
                STATUS_CODE::PARAMETER_ERROR,
                '参数不能为空');
        }
        if (!empty($data['mobile']) && !check_mobile($data['mobile'])) {
            return result(
                STATUS_CODE::PARAMETER_ERROR,
                '手机号码格式错误');
        }
        if (!empty($data['email']) && !check_email($data['email'])) {
            return result(
                STATUS_CODE::PARAMETER_ERROR,
                '邮箱格式错误');
        }
        if (!empty($data['password']) &&
            !preg_match('/^[a-zA-Z\d_]{8,}$/', $data['password'])) {
            return result(
                STATUS_CODE::PARAMETER_ERROR,
                '密码格式错误');
        }
        if ($data['mobile']) {
            $count = $model
                ->where(
                    'mobile',
                    $data['mobile']
                )->where('uuid', '<>', $uuid)
                ->count();
            if ($count > 0) {
                return result(
                    STATUS_CODE::PARAMETER_ERROR,
                    '手机号码已存在');
            }

        }
        // 检测邮箱是否存在
        if ($data['email']) {
            $count = $model
                ->where(
                    'email',
                    $data['email']
                )->where('uuid', '<>', $uuid)
                ->count();
            if ($count > 0) {
                return result(
                    STATUS_CODE::PARAMETER_ERROR,
                    '邮箱已存在');
            }

        }
        if (empty($data['password'])) {
            unset($data['password']);
        } else {
            $password = password(strtolower(trim($data['password'])));
            unset($data['password']);
            $data = array_merge($data, $password);
        }

        $result = $model
            ->updateByUUID($uuid, $data);
        return result(STATUS_CODE::SUCCESS, $result);
    }

    /**
     * @purpose 删除管理员
     * @param Request $request
     * @return array
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function delete(Request $request)
    {
        $uuid  = $request->post('uuid');
        $model = new Administrator();
        if (empty($uuid)) {
            return result(
                STATUS_CODE::PARAMETER_ERROR,
                '参数错误');
        }
        $result = $model
            ->where(['uuid' => $uuid])
            ->delete();
        if ($result) {
            return result();
        }
        return result(
            STATUS_CODE::TO_TRASH_FAIL,
            '删除失败');
    }

    /**
     * @purpose 禁用/解禁管理员
     * @param Request $request
     * @return array
     */
    public function isDisable(Request $request)
    {
        $uuid   = $request->post('uuid');
        $type   = $request->post('type');
        $model  = new Administrator();
        $result = null;
        switch ($type) {
            case 1:
                $result = $model->enable($uuid);
                break;
            case 2:
                $result = $model->disable($uuid);
                break;
        }
        if ($result) {
            return result(
                STATUS_CODE::SUCCESS,
                '操作成功');
        }
        return result(
            STATUS_CODE::FAIL,
            '操作失败!');
    }

    /**
     * @purpose 管理员授权接口
     * @param Request $request
     * @return array
     * @throws \Exception
     */
    public function authorization(Request $request)
    {
        $data          = $request->post();
        $authorization = new Authorization();
        $result        = $authorization->authorization($data);
        if ($result) {
            return result(
                STATUS_CODE::SUCCESS,
                $result,
                '授权成功');
        }
        return result(
            STATUS_CODE::FAIL,
            '授权失败');
    }
}