<?php

namespace NaturalGao\PhpPay\Services;

use EasyWeChat\Pay\Application;
use NaturalGao\PhpPay\Exception\PayException;
use NaturalGao\PhpPay\Interface\ThirdPartyPayInterface;
use NaturalGao\PhpPay\PayService;

class WechatPayService extends PayService implements ThirdPartyPayInterface
{
    public static $ServiceNmae = 'wechat';

    public static $PayFactory;

    public static $Client;

    public static $Instance;

    public function __construct(array $config)
    {
        self::$PayFactory = new Application($config);
        self::$Client = self::$PayFactory->getClient();
    }

    /**
     * 创建订单
     * @param array $order
     * @return mixed
     */
    public static function createOrder(array $order)
    {
        self::serviceUndeDevelopment();
    }

    /**
     * 创建二维码下单
     * @param array $order
     * @return mixed
     */
    public static function createQrCodeOrder(array $order)
    {
        $body = [
            'mchid' => (string) self::$PayFactory->getMerchant()->getMerchantId(),
            'appid' => self::$PayFactory->getConfig()->get('app_id')
        ];

        $body = array_merge($body, $order);

        $response = self::$Client->postJson('/v3/pay/transactions/native', $body);

        return self::responseHandle(createErweima($response['code_url']));
    }


    /**
     * 退款
     * @param array $order
     * @return mixed
     */
    public static function refundOrder(array $order)
    {
        $response = self::$Client->postJson('/v3/refund/domestic/refunds', $order);
        return self::responseHandle($response->getContent());
    }

    /**
     * 查询微信订单
     * @param array $order
     * @return mixed
     */
    public static function queryWechatOrder(array $order)
    {
        if (!isset($order['transaction_id']) || !$order['transaction_id']) {
            throw new PayException('订单号不能为空');
        }

        $response = self::$Client->get('/v3/pay/transactions/id/' . $order['transaction_id'], [
            'mchid' => (string) self::$PayFactory->getMerchant()->getMerchantId()
        ]);

        return self::responseHandle($response->getContent());
    }

    /**
     * 查询商户订单
     * @param array $order
     * @return mixed
     */
    public static function queryOrder(array $order)
    {
        if (!isset($order['out_trade_no']) || !$order['out_trade_no']) {
            throw new PayException('订单号不能为空');
        }

        $response = self::$Client->get('/v3/pay/transactions/out-trade-no/' . $order['out_trade_no'], [
            'mchid' => (string) self::$PayFactory->getMerchant()->getMerchantId()
        ]);

        return self::responseHandle($response->getContent());
    }

    /**
     * 查询退款
     * @param array $order
     * @return mixed
     */
    public static function queryRefundOrder(array $order)
    {
        if (!isset($order['out_refund_no']) || !$order['out_refund_no']) {
            throw new PayException('订单号不能为空');
        }

        $response = self::$Client->get('/v3/refund/domestic/refunds/' . $order['out_refund_no']);

        return self::responseHandle($response->getContent());
    }

    /**
     * 关闭订单
     * @param array $order
     * @return mixed
     */
    public static function closeOrder(array $order)
    {
        if (!isset($order['out_trade_no']) || !$order['out_trade_no']) {
            throw new PayException('订单号不能为空');
        }

        $response = self::$Client->postJson('/v3/pay/transactions/out-trade-no/' . $order['out_trade_no'] . '/close', [
            'mchid' => (string) self::$PayFactory->getMerchant()->getMerchantId()
        ]);

        return self::responseHandle($response->getContent());
    }

    /**
     * 撤销订单
     * @param array $order
     * @return mixed
     */
    public static function cancelOrder(array $order)
    {
        self::serviceUndeDevelopment();
    }
}
