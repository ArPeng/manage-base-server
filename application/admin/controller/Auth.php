<?php
/**
 * Created by PhpStorm.
 * User: peter
 * Date: 2018/5/28
 * Time: 下午5:31
 */

namespace app\admin\controller;


use app\admin\logic\Administrator;
use extend\STATUS_CODE;
use think\App;

class Auth extends Base
{

    public static $user_info = [], $uid, $uuid;

    /**
     * Auth constructor.
     * @param App|null $app
     * @throws \Exception
     */
    public function __construct(App $app = null)
    {
        parent::__construct($app);
        self::authToken();
    }

    /**
     * @return bool
     * @throws \Exception
     */
    private function authToken() {
        if (!self::$token || self::$token === 'undefined') {
            exception(
                '登录已过期!',
                STATUS_CODE::EXPIRED_TOKEN);
        }
        $administrator = new \app\model\Administrator();
        self::$user_info = $administrator->userInfoByToken(self::$token);
        if (empty(self::$user_info)) {
            exception(
                '登录已过期',
                STATUS_CODE::EXPIRED_TOKEN);
        }
        self::$uid = self::$user_info['uid'];
        self::$uuid = self::$user_info['uuid'];
        return true;
    }
}