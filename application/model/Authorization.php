<?php
/**
 * @Purpose: 授权表数据模型
 * @Author Peter
 * @Email yangchenpeng@qq.com
 * @Date   2017/9/17 17:26
 */

namespace app\model;

use extend\STATUS_CODE;

class Authorization extends Base
{
    protected $pk = 'uid';
    // 此处表名请务必设置为public
    public $table = 'authorization';

    /**
     * @purpose 用户授权, 不存在就新增,存在就修改
     * @param array $data
     * @return Authorization|int
     * @throws \Exception
     */
    public function authorization(array $data)
    {
        $_data = [];
        if (empty($data['uid'])) {
            exception(
                '缺少用户ID!',
                STATUS_CODE::PARAMETER_ERROR

            );
        }
        $_data['uid']    = (int)$data['uid'];
        $_data['groups'] = $data['group_id'] ? $data['group_id'] : '';
        $_data['rules']  = $data['rules'] ? $data['rules'] : '';
        if ($this->total(['uid' => $_data['uid']]) > 0) {
            return $this->update($_data);
        } else {
            return (int)$this->save($_data);
        }
    }

    /**
     * @purpose 根据条件获取数据条数
     * @param array $where
     * @return int
     */
    public function total(array $where = [])
    {
        return (int)$this
            ->where($where)
            ->count();
    }
}