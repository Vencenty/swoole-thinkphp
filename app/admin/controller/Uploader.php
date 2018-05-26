<?php
namespace app\admin\controller;

use app\common\Response;

class Uploader
{
    // 上传文件
    public function index()
    {
        // 上传文件
        $file = request()->file('file');
        $info = $file->move('../public/static/upload/');


        if($info) {
            $data = [
                'image' => config('server.host') . '/upload/' . $info->getSaveName()
            ];
            return Response::json(0, '上传成功', $data);
        }
        return Response::json(2001, '图片上传失败');
    }
}