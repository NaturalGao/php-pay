<?php

namespace NaturalGao\PhpPay\Interface;

interface ThirdPartyPayInterface
{
    /**
     * 构造函数，初始化配置
     * @param array $config
     * @return void
     */
    public function __construct(array $config);

    /**
     * 创建下单
     * @param array $order
     * @return mixed
     */
    public static function createOrder(array $order);

    /**
     * 生成二维码订单
     * @param array $order
     * @return mixed
     */
    public static function createQrCodeOrder(array $order);

    /**
     * 订单退款
     * @param array $order
     * @return mixed
     */
    public static function refundOrder(array $order);

    /**
     * 查询订单
     * @param array $order
     * @return mixed
     */
    public static function queryOrder(array $order);

    /**
     * 退款订单查询
     * @param array $order
     * @return mixed
     */
    public static function queryRefundOrder(array $order);

    /**
     * 关闭订单
     * @param array $order
     * @return mixed
     */
    public static function closeOrder(array $order);


    /**
     * 撤销订单
     * @param array $order
     * @return mixed
     */
    public static function cancelOrder(array $order);

    /**
     * response 处理
     * @param $data
     * @return array
     */
    public static function responseHandle($data);
}
