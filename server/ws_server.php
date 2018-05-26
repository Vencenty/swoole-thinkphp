<?php

use app\common\Redis;

class WS
{
    protected $server;

    public function __construct()
    {
        $this->server = new swoole_websocket_server('0.0.0.0', '8888');

        $redis = new \Redis();
        $redis->connect('127.0.0.1', '6379');
        $redis->flushAll();



        $this->server->set([
            'enable_static_handler' => true,
            'document_root'         => '/www/swoole/tp5/public/static/live',
            'worker_num'            => 4,
            'task_worker_num'       => 10,
        ]);

        $this->server->on('WorkerStart', function () {
            require __DIR__ . '/../thinkphp/base.php';
            think\Container::get('app')->run()->send();
        });

        $this->server->on('request', function ($request, $response) {
            $this->server->reload();

            $this->initializeGlobalVariables($request);

            $_SERVER['ws_server'] = $this->server;

            ob_start();
            try {
                think\Container::get('app')->run()->send();
            } catch (Exception $e) {
//                 TODO
            }
            $content = ob_get_contents();
            ob_end_clean();
            $response->end($content);
        });

        $this->server->on('task', function ($server, $task_id, $worker_id, $data) {
            $params = $data['params'];
            $method = $data['method'];
            $task = new \app\common\Task();
            return $task->$method($params);
        });

        $this->server->on('finish', function ($server, $task_id, $data) {
            echo "任务:{$task_id}完成;回调数据为{$data}\n";
        });

        $this->server->on('open', function ($server, $request) {
            Redis::getInstance()->sAdd(config('chart.online_user'), $request->fd);
        });

        $this->server->on('message', function () {

        });

        $this->server->start();
    }

    protected function initializeGlobalVariables($request)
    {
        $_GET = $_POST = [];

        if (isset($request->get)) {
            foreach ($request->get as $k => $v) {
                $_GET[$k] = $v;
            }
        }
        if (isset($request->post)) {
            foreach ($request->post as $k => $v) {
                $_POST[$k] = $v;
            }
        }
        if (isset($request->header)) {
            foreach ($request->header as $k => $v) {
                $_SERVER[strtoupper($k)] = $v;
            }
        }
        if (isset($request->server)) {
            foreach ($request->server as $k => $v) {
                $_SERVER[strtoupper($k)] = $v;
            }
        }
    }

}

new WS();