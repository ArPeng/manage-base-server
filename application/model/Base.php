<?php
/**
 * @purpose 模型基础类
 */
namespace app\model;

use think\Model;
use think\model\concern\SoftDelete;

abstract class Base extends Model
{
    protected $pk           = 'id';
    protected $createTime   = 'create_at';
    protected $updateTime   = 'update_at';
    // 软删除
    use SoftDelete;
    protected $deleteTime = 'delete_at';
    protected $defaultSoftDelete = 0;

    /**
     * @purpose 重载静态方法
     * @param $method
     * @param $arguments
     * @return array|int|mixed|\PDOStatement|string|Model|null
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public static function __callStatic ($method, $arguments) {
        switch ($method) {
            case 'total':
                return self::getTotal($arguments[0]);
                break;
            case 'info':
                return self::getInfo($arguments[0], $arguments[1]);
                break;
            case 'items':
                return self::getItems(
                        $arguments[0],
                        $arguments[1],
                        $arguments[2],
                        $arguments[3],
                        $arguments[4]
                    );
                break;
        }
    }
    /**
     * @purpose 根据条件获取数据条数
     * @param array $where
     * @return int
     */
    private static function getTotal ($where = []): int
    {
        return self::M()
            ->where($where)
            ->count();
    }
    /**
     * @purpose 获取单条数据信息
     * @param array $where
     * @param string $fields
     * @return array|null|\PDOStatement|string|Model
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    private static function getInfo($where = [], $fields = '*')
    {
        return self::M()
            ->where($where)
            ->field($fields)
            ->find();
    }

    /**
     * @purpose 获取数据列表
     * @param array $where
     * @param string $fields
     * @param int $page
     * @param int $size
     * @param string $order
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    private static function getItems (
        $where = [],
        string $fields = '*',
        int $page = 1,
        int $size = 50,
        $order = ''): array
    {
        $model      = self::M();
        $total      = $model
            ->where($where)
            ->count(1);
        $list = $model
            ->where($where)
            ->page($page, $size)
            ->order($order)
            ->field($fields)
            ->select();
        return [
            'total' => $total,
            'list' => $list
        ];
    }

    /**
     * @purpose 获取模型
     * @return Base
     */
    public static function M()
    {
        return new static();
    }
}