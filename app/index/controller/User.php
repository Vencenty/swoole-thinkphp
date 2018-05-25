<?php

namespace app\index\controller;

use app\common\Redis;
use app\common\Response;
use think\Controller;

class User extends Controller
{

    public function login()
    {
        $mobile = request()->get('phone_num');
        $code = request()->get('code');

        $redisCode = Redis::getInstance()->get(Redis::smsKey($mobile));

        if($redisCode != $code) {
            return Response::json(1003, '验证码错误或者已经过期');
        }

        return Response::json(0, '登陆成功');
    }

    public function sendSms()
    {
        $mobile = request()->get('mobile');

        if (strlen($mobile) != 11) {
            return Response::json(1001, '手机号码错误');
        }

        $server = $_SERVER['ws_server'];

        // 生成四位数验证码
        $code = rand(1000, 9999);

        $taskData = [
            'method'    => 'sendSms',
            'params'    => [
                'code'   => $code,
                'mobile' => $mobile
            ]
        ];

        $server->task($taskData);

        return Response::json(0, "验证码为:".$code);

    }
}