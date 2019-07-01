<?php
/**
 * @Purpose: 登录日志数据模型
 * @Author Peter
 * @Email yangchenpeng@qq.com
 * @Date   2017/9/17 17:34
 */

namespace app\model;


use extend\STATUS_CODE;

class Setting extends Base
{

    protected $pk = 'key';
    // 此处表名请务必设置为public
    public $table = 'setting';

    /**
     * @purpose 获取配置
     * @param string $key
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @throws \Exception
     */
    public function getConfigure(string $key): array
    {
        if (empty($key)) {
            exception(
                '缺少获取配置的key!',
                STATUS_CODE::PARAMETER_ERROR
            );
        }
        $result = $this
            ->where('key', $key)
            ->field('value')
            ->find();
        if (!$result) {
            return [];
        }
        return json_decode($result->value, true);
    }
    /**
     * @purpose 设置配置,如果存在则更新
     * @param string $key
     * @param array $value
     * @param string $description
     * @return bool
     * @throws \Exception
     */
    public function setConfigure(
        string $key,
        array $value = [],
        string $description = ''): bool
    {
        if (empty($key)) {
            exception(
                '缺少配置key!',
                STATUS_CODE::PARAMETER_ERROR
            );
        }
        $result  = false;
        // 不存在则新增
        if (!$this->hasKey($key)) {
            $result = $this
                ->data([
                    'key'           => $key,
                    'value'         => json_decode($value),
                    'description'   => $description
                ])->save();
        } else { // 存在则更新
            $data = [];
            if (!empty($value)) {
                $data['value']          = json_encode($value);
            }
            if (!empty($description)) {
                $data['description']    = $description;
            }
            $result = $this
                ->save($data, ['key' => $key]);
        }

        return (bool) $result;
    }

    /**
     * @purpose 检测配置是否存在
     * @param string $key
     * @return bool
     */
    public function hasKey (string $key) : bool
    {
        $result = $this
            ->where('key', $key)
            ->count();
        return (bool) $result;
    }
}