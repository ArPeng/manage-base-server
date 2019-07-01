<?php
/**
 * @Purpose: 用户组表模型
 * @Author Peter
 * @Email yangchenpeng@qq.com
 * @Date   2017/9/17 17:24
 */

namespace app\model;
use extend\STATUS_CODE;

class Group extends Base
{
    protected $pk = 'id';
    // 此处表名请务必设置为public
    public $table = 'group';

    /**
     * @purpose 获取所有管理组
     * @param string $fields
     * @return array|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function allGroup(string $fields)
    {
        return $this
            ->field($fields)
            ->select();
    }

    /**
     * @purpose 通过ID获取单条数据
     * @param int $id
     * @param string $fields
     * @return array|\PDOStatement|string|\think\Model|null
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getOneById(
        int $id,
        string $fields = 'id,name,rules,descriptions,create_at,update_at')
    {
        return $this
            ->where('id', $id)
            ->field($fields)
            ->find();
    }

    /**
     * @purpose 添加一个用户组
     * @param array $data
     * @return int
     * @throws \Exception
     */
    public function addOne(array $data)
    {
        $_data = [];
        if (empty($data['name'])) {
            exception(
                '缺少名称!',
                STATUS_CODE::PARAMETER_ERROR
            );
        }
        $_data['name']          = (string)$data['name'];
        $_data['rules']         = !empty($data['rules']) ? (string)$data['rules'] : '';
        $_data['descriptions']  = !empty($data['descriptions']) ? (string)$data['descriptions'] : '';
        return (int)$this
            ->data($_data)
            ->save();
    }

    /**
     * @purpose 修改数据
     * @param array $data 需要修改的数据, 数据中必须包含主键 `id`
     * @param array $data
     * @return int
     * @throws \Exception
     */
    public function updateById(array $data)
    {
        if (empty($data['name'])) {
            exception(
                '缺少名称!',
                STATUS_CODE::PARAMETER_ERROR
            );
        }
        if (isset($data['rules']) && empty($data['rules'])) {
            unset($data['rules']);
        }
        return (int)$this->save($data, ['id' => $data['id']]);
    }

    /**
     * @purpose 分配权限
     * @param int $id
     * @param string $rules
     * @return int
     * @throws \Exception
     */
    public function updateRulesById(int $id, string $rules)
    {
        if (empty($id)) {
            exception(
                '缺少ID!',
                STATUS_CODE::PARAMETER_ERROR
            );
        }
        if (empty($rules)) {
            exception(
                '缺少权限!',
                STATUS_CODE::PARAMETER_ERROR
            );
        }
        return (int)$this->save(['rules' => $rules], ['id' => $id]);
    }

    /**
     * @purpose 通过主键ID删除权限
     * @param string $id 支持批量删除 1,2,3,4
     * @return int
     */
    public function deleteById($id)
    {
        return (int)self::destroy($id);
    }

    /**
     * @purpose 获取管理员列表
     * @param int $page
     * @param int $pageSize
     * @param array $where
     * @param string $order
     * @param string $fields
     * @return array|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getItem(
        int $page = 1,
        int $pageSize = 20,
        array $where = [],
        $order = 'create_at desc',
        $fields = 'id,name,rules,descriptions,create_at,update_at'
    )
    {
        return $this
            ->where($where)
            ->field($fields)
            ->page($page, $pageSize)
            ->order($order)
            ->select();
    }

    /**
     * @purpose 根据条件获取数据数量
     * @param array $where
     * @return int|string
     */
    public function total(array $where = [])
    {
        return (int)$this
            ->where($where)
            ->count();
    }

    /**
     * @purpose 通过ID获取rules
     * @param string $ids
     * @return string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getRulesByIds(string $ids)
    {
        $result = $this
            ->whereIn('id', $ids)
            ->field('rules')
            ->select();
        $rules = '';
        foreach ($result as $v) {
            $rules .= $v['rules'] . ',';
        }
        $rules = explode(',', rtrim($rules, ','));
        $rules = array_filter($rules);
        return implode(',', $rules);
    }
}