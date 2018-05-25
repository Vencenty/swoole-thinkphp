<?php

namespace app\common;

class Response
{
    public static function json(int $errno, string $errmsg, $data = [])
    {
        $result = [
            'errno' => $errno,
            'errmsg'    => $errmsg,
        ];

        if ($data) {
            $result['data'] = $data;
        }

        return print_r( json_encode($result), true );
    }
}