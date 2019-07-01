<?php
/**
 * Created by PhpStorm.
 * User: peter
 * Date: 2019/2/22
 * Time: 5:40 PM
 */

namespace app\admin\controller;


use FFMpeg\Coordinate\TimeCode;
use FFMpeg\FFMpeg;
use think\facade\Env;
use think\facade\Log;
use think\Request;

class Test extends Base
{
    public function test(Request $request)
    {
        return ['aaa'];
//        $queue  = new self();
//        $times = $request->get('times');
//        $queue->push('test', ['times' => $times]);
    }
}