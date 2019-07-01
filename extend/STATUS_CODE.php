<?php
/**
 * Created by PhpStorm.
 * User: peter
 * Date: 2017/11/10
 * Time: 上午10:24
 */

namespace extend;


class STATUS_CODE
{
    // 请求成功
    const SUCCESS = 10000;
    // 参数错误
    const PARAMETER_ERROR = 10001;
    // 请求错误
    const FAIL = 10002;
    // 未找到token
    const TOKEN_NOT_FOUND = 10003;
    // token已过期
    const EXPIRED_TOKEN = 10004;
    // 账号被禁用
    const ACCOUNT_DISABLED = 10005;
    // 移入回收站失败
    const TO_TRASH_FAIL = 10006;
    // 数据创建失败
    const CREATE_FAIL = 10007;
    // 数据更新失败
    const UPDATE_FAIL = 10008;
    // 删除数据失败
    const DELETE_FAIL = 10009;
    // 没有数据或根据条件没有查询到数据
    const DATA_NOT_FIND = 10010;
    // 权限不足
    const PERMISSION_DENIED = 10011;
    // 上传错误
    const UPLOAD_ERROR = 10012;
    // 订单状态错误
    const ORDER_STATUS_ERROR = 10013;

}