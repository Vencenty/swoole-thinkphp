<?php

namespace app\index\controller;

use app\common\Redis;
use app\common\Response;

class Chart
{
    public function push()
    {
        $server = $_SERVER['ws_server'];

        $connections = Redis::getInstance()->sMembers(config('chart.online_user'));

        $data = [
            'time'  => date('y-m-d H:i:s', time()),
            'user'  => '用户_'.rand(10000,99999),
            'content'         => $_GET['content'],
            'connections_num' => count($connections),
        ];

        foreach ($connections as $fd) {
            $server->push($fd, json_encode($data));
        }

        return Response::json(0, '推送成功');
    }
}