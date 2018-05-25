<?php

namespace app\common;

class Task
{
    public function sendSms($data)
    {
        $mobile = $data['mobile'];
        $code = $data['code'];

        $result = Redis::getInstance()->set(Redis::smsKey($mobile), $code, config('redis.expire_time'));

        if(!$result) {
            return Response::json(1002, 'redis写入失败');
        }

        return '发送成功';
    }
}