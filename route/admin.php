<?php
// IP绑定到admin模块
Route::domain('admin-api', 'admin');
Route::domain('admin-api', function () {
    // 通过密码登录
    Route::post('sign.by_password', 'Sign/signByPassword');
    // 检测token以及权限
    Route::post('sign.verification', 'Sign/verification');
    // 清除Token
    Route::post('sign.clear_token', 'Sign/clearToken');
    /*********************管理员相关**********************/
    // 创建管理员
    Route::post('user.create', 'User/create');
    // 获取管理员列表
    Route::get('user.list', 'User/items');
    // 获取管理员信息
    Route::get('user.info', 'User/info');
    // 更新管理员
    Route::post('user.update', 'User/update');
    // 删除管理员
    Route::post('user.delete', 'User/delete');
    // 禁用/解禁管理员
    Route::post('user.is_disable', 'User/isDisable');
    // 管理员授权
    Route::post('user.authorization', 'User/authorization');
    /*************************权限相关***************************/
    // 创建权限
    Route::post('rule.create', 'Rule/create');
    // 更新权限
    Route::post('rule.update', 'Rule/update');
    // 删除权限
    Route::post('rule.delete', 'Rule/delete');
    // 根据pid获取权限
    Route::get('rule.get_list_by_pid', 'Rule/getListByPid');
    // 获取无限极格式的数据
    Route::get('rule.infinite', 'Rule/infinite');
    // 通过ID获取单条权限信息
    Route::get('rule.get_rule_info_by_id', 'Rule/getRuleInfoById');
    // 获取控制面板菜单
    Route::get('rule.dashboard', 'Rule/dashboard');
    // 获取侧边栏菜单
    Route::get('rule.menu', 'Rule/menu');
    // 获取指定权限下的所有子权限
    Route::get('rule.identification', 'Rule/identification');
    /****************************管理组相关********************/
    // 新增管理组
    Route::post('group.create', 'Group/create');
    // 更新管理组
    Route::post('group.update', 'Group/update');
    // 删除管理组
    Route::post('group.delete', 'Group/delete');
    // 通过ID获取单条管理组
    Route::get('group.get_group_info_by_id', 'Group/getGroupInfoById');
    // 获取管理组列表
    Route::get('group.get_list', 'Group/getList');
    // 给用户组授权
    Route::post('group.authorization', 'Group/authorization');
    // 获取所有用户组
    Route::get('group.all', 'Group/all');
    /**************************配置相关********************/
    // 获取权限白名单
    Route::get('configure.rule_white_list', 'Configure/getRuleWhiteList');
    // 设置权限白名单
    Route::get('configure.set_rule_white_list', 'Configure/setRuleWhiteList');
});