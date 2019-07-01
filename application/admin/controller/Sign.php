<?php

namespace app\admin\controller;

use app\model\Administrator;
use app\model\Setting;
use extend\STATUS_CODE;
use think\Request;

class Sign
{

    /**
     * @purpose 通过邮箱或手机号码+密码登录
     * @param string $sign 邮箱或手机号码
     * @param string $password 密码
     * @param Request $request
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function signByPassword(Request $request): array
    {
        $sign     = $request->post('sign');
        $password = $request->post('password');

        // TODO: Implement signByPassword() method.
        if (empty($password)) {
            return result(
                STATUS_CODE::PARAMETER_ERROR,
                '密码不可为空');
        }

        $sign      = get_sign_type($sign);
        $model     = new Administrator();

        $user_info = $model
            ->where(
                $sign['type'],
                $sign[$sign['type']])
            ->field('password,encrypt,uid,
            status,avatar,email,mobile,name')
            ->find();
        if (empty($user_info)) {
            return result(
                STATUS_CODE::FAIL,
                '管理员不存在');
        }
        if ($user_info['status'] === 2) {
            return result(
                STATUS_CODE::ACCOUNT_DISABLED,
                '你账号已被禁用,请联系管理员!');
        }
        if ($user_info['password'] !==
            password($password, $user_info['encrypt'])) {
            return result(
                STATUS_CODE::PARAMETER_ERROR,
                '账号或密码错误!');
        }
        $token = strtolower(getUUID());
        RE_TOKEN:
        $token = strtolower(getUUID());

        $count = $model
            ->where('token', $token)
            ->count();
        if ($count > 0) {
            goto RE_TOKEN;
        }

        $result = $model
            ->save(
                [
                    'token'                 => $token,
                    'expiration_date_token' => time() + (3600 * 24 * 10),
                ],
                ['uid' => $user_info['uid']]
            );

        if ($result) {
            return result(
                STATUS_CODE::SUCCESS,
                [
                    'token' => $token,
                    'info'  => [
                        'avatar' => $user_info['avatar'],
                        'email'  => $user_info['email'],
                        'mobile' => $user_info['mobile'],
                        'name'   => $user_info['name'],
                        'status' => $user_info['status'],
                    ]
                ]
            );
        }
        return result(STATUS_CODE::FAIL, '登录失败!');
    }

    /**
     * @purpose 通过uid获取当前用户所有的权限ID
     * @param Request $request
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function verification(Request $request): array
    {
        $token          = $request->post('token');
        $identification = $request->post('identification');
        if (!$token) {
            return result(
                STATUS_CODE::PARAMETER_ERROR,
                '令牌(token)不能为空');
        }
        $model  = new Administrator();
        $fields = 'uid,uuid,mobile,email,name,
        avatar,status,expiration_date_token';
        $result = $model->getUserInfoByToken(
            $token,
            $fields);
        if (!$result) {
            return result(
                STATUS_CODE::SUCCESS,
                ['status' => STATUS_CODE::TOKEN_NOT_FOUND],
                '请登录!'
            );
        }
        if ($result['expiration_date_token'] < time()) {
            return result(
                STATUS_CODE::EXPIRED_TOKEN,
                ['status' => STATUS_CODE::EXPIRED_TOKEN],
                '请登录!'
            );
        }
        $result['expiration_date_token'] = time() + (3600 * 24 * 10);
        unset($result['expiration_date_token']);
        // 重置token过期时间
        $model->setTokenExpired($token, time() + (3600 * 24 * 10));
        // 获取权限白名单,若当前权限在白名单内,则不用验证
        $setting_model = new Setting();
        $white_list_result = $setting_model
            ->getConfigure('rule_white_list');
        $white_list = [];
        foreach ($white_list_result as $v) {
            array_push($white_list, $v['identification']);
        }
        if (in_array($identification,$white_list)) {
            return result(
                STATUS_CODE::SUCCESS,
                [
                    'status' => STATUS_CODE::SUCCESS,
                    'info' => $result
                ]
            );
        }
        // 如果不在白名单则进行权限验证
        $rule = new \app\model\Rule();
        $rules_ids      = $rule->getRulesByUid($result['uid']);
        $rules_ids      = explode(',', $rules_ids);
        $identification = $rule->identificationToId($identification);
        if (!in_array($identification['id'], $rules_ids)) {
            return result(
                STATUS_CODE::SUCCESS,
                [
                    'status' => STATUS_CODE::PERMISSION_DENIED
                ],
                'Permission denied'
            );
        }
        return result(
            STATUS_CODE::SUCCESS,
            [
                'status' => STATUS_CODE::SUCCESS,
                'info' => $result
            ],
            'Permission denied'
        );
    }

    /**
     * @purpose 清除Token
     * @param Request $request
     * @return array
     */
    public function clearToken(Request $request): array
    {
        $token = $request->post('token');
        if (!$token) {
            return result(
                STATUS_CODE::ACCOUNT_DISABLED,
                '令牌(token)不能为空');
        }
        $model = new Administrator();
        $model
            ->save(
                ['token' => ''],
                ['token' => $token]);
        return result();
    }
}
