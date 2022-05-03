<?php

namespace NaturalGao\PhpPay;

use NaturalGao\PhpPay\Exception\PayException;

class PayService
{

    /**
     * 初始化配置
     * @param array $config
     * @return instance
     */
    public static function initConfig(array $config)
    {
        if (!((static::class)::$Instance instanceof static)) {
            (static::class)::$Instance = new static($config);
        }
        return (static::class)::$Instance;
    }

    /**
     * response 处理
     * @param $data
     * @return array
     */
    public static function responseHandle($data)
    {
        $type = (static::class)::$ServiceNmae;
        return compact('type', 'data');
    }

    /**
     * 服务未开发异常
     */
    protected static function serviceUndeDevelopment()
    {
        throw new PayException('服务未开发');
    }
}
