<?php

namespace app\common;

use Redis as Predis;

class Redis
{
    protected static $userPrefix = 'user_';
    protected static $smsPrefix = 'sms_';
    protected static $_instance;
    protected $redis;

    public static function getInstance()
    {
        if (!self::$_instance instanceof self) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public static function smsKey($mobile)
    {
        return self::$smsPrefix . $mobile;
    }

    public static function userKey($mobile)
    {
        return self::$userPrefix . $mobile;
    }

    private function __construct()
    {
        $this->redis = new Predis();
        $this->redis->connect(
            config('redis.host'),
            config('redis.port')
        );
    }

    public function get($key)
    {
        return $this->redis->get($key);
    }

    public function set($key, $value, $expire_time = null)
    {
        $value = is_array($value) ? json_encode($value) : $value;

        return is_null($expire_time) ?
            $this->redis->set($key, $value) :
            $this->redis->setex($key, $expire_time, $value);
    }

    public function sAdd($key, $value)
    {
        return $this->redis->sAdd($key, $value);
    }

    public function sRem($key, $value)
    {
        return $this->redis->sRem($key, $value);
    }

    public function sMembers($key)
    {
        return $this->redis->sMembers($key);
    }

    public function flushAll()
    {
        return $this->redis->flushAll();
    }
}