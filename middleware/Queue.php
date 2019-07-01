<?php
/**
 * Created by PhpStorm.
 * User: peter
 * Date: 2018/9/30
 * Time: 上午11:57
 */

namespace middleware;
use think\Exception;
trait Queue
{
    /**
     * @purpose 发布消息队列
     * @param string $name
     * @param null $data
     * @return bool
     * @throws Exception
     */
    public function push ($name = '', $data = null)
    {
        if (!$name) {
            return false;
        }
        $_name = $name;
        if(strpos($_name,'.') !== false) {
            $_name = explode('.',$_name);
            $_name = array_map(function ($item) {
                return ucfirst(strtolower(trim($item)));
            }, $_name);
            $_name = implode('',$_name);
        } else {
            $_name = ucfirst(strtolower(trim($_name)));
        }
        $jobHandlerClassName = 'app\job\\'.$_name;
        if (!class_exists($jobHandlerClassName)) {
            throw  new Exception('job handler "'.$jobHandlerClassName.'" class exits');
        }
        return \think\Queue::push($jobHandlerClassName, $data, strtolower(trim($name)));
    }
}