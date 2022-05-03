<?php

namespace NaturalGao\PhpPay;

use NaturalGao\PhpPay\Interface\ThirdPartyPayInterface;

class Factory
{
    private static $Instance;

    private static $Service;

    public function __construct(ThirdPartyPayInterface $Service)
    {
        self::$Service = $Service;
    }

    /**
     * 初始化配置
     * @param array $config
     * @return PayService instance
     */

    public static function init(string $driver, array $config)
    {
        if (!(self::$Instance instanceof self)) {
            self::$Instance = new self(self::getDriver($driver, $config));
        }
        return self::$Instance;
    }

    /**
     * 获取服务
     * @return PayInterface
     */
    public static function service()
    {
        return self::$Service;
    }

    /**
     * 获取驱动实例
     */
    private static function getDriver(string $driver, array $config)
    {
        $class_name = __NAMESPACE__ . '\\Services\\' . classNameToHump($driver) . 'PayService';

        return new $class_name($config);
    }
}
