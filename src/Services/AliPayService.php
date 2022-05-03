<?php

namespace NaturalGao\PhpPay\Services;

use Alipay\EasySDK\Kernel\Config;
use Alipay\EasySDK\Kernel\Factory;
use Alipay\EasySDK\Kernel\Util\ResponseChecker;
use NaturalGao\PhpPay\Exception\PayException;
use NaturalGao\PhpPay\Interface\ThirdPartyPayInterface;
use NaturalGao\PhpPay\PayService;

class AliPayService extends PayService implements ThirdPartyPayInterface
{
    public static $ServiceNmae = 'alipay';

    private static $PayFactory;

    public static $Instance;

    public function __construct(array $config)
    {
        self::$PayFactory = Factory::setOptions($this->getOptions($config));
    }

    private function getOptions(array $config)
    {
        $options = new Config();
        foreach ($config as $key => $value) {
            $options->$key = $value;
        }
        return $options;
    }

    /**
     * 创建下单
     * @param array $order
     * @return mixed
     */
    public static function createOrder(array $order)
    {
        if (!isset($order['subject']) || !$order['subject']) {
            throw new PayException('订单标题不能为空');
        }

        if (!isset($order['out_trade_no']) || !$order['out_trade_no']) {
            throw new PayException('订单号不能为空');
        }

        if (!isset($order['total_amount']) || !$order['total_amount']) {
            throw new PayException('订单金额不能为空');
        }

        if (!isset($order['buyer_id']) || !$order['buyer_id']) {
            throw new PayException('买家id不能为空');
        }

        $result = self::$PayFactory->payment()
            ->common()
            ->create($order['subject'], $order['out_trade_no'], $order['total_amount'], $order['buyer_id']);
        self::responseChecker($result);
        return self::responseHandle(createErweima($result->qrCode));
    }

    /**
     * 创建下单二维码
     * @param array $order
     * @return mixeds
     */
    public static function createQrCodeOrder(array $order)
    {
        if (!isset($order['subject']) || !$order['subject']) {
            throw new PayException('订单标题不能为空');
        }

        if (!isset($order['out_trade_no']) || !$order['out_trade_no']) {
            throw new PayException('订单号不能为空');
        }

        if (!isset($order['total_amount']) || !$order['total_amount']) {
            throw new PayException('订单金额不能为空');
        }

        $result = self::$PayFactory->payment()
            ->faceToFace()
            ->preCreate($order['subject'], $order['out_trade_no'], $order['total_amount']);
        self::responseChecker($result);
        return self::responseHandle(createErweima($result->qrCode));
    }

    /**
     * 订单退款
     * @param array $order
     * @return mixed
     */
    public static function refundOrder(array $order)
    {
        if (!isset($order['out_trade_no']) || !$order['out_trade_no']) {
            throw new PayException('订单号不能为空');
        }

        if (!isset($order['refund_amount']) || !$order['refund_amount']) {
            throw new PayException('退款金额不能为空');
        }

        $result = self::$PayFactory->payment()
            ->common()
            ->refund($order['out_trade_no'], $order['refund_amount']);

        self::responseChecker($result);

        return self::responseHandle($result);
    }

    /**
     * 订单查询
     * @param array $order
     * @return mixed
     */
    public static function queryOrder(array $order)
    {
        if (!isset($order['out_trade_no']) || !$order['out_trade_no']) {
            throw new PayException('订单号不能为空');
        }

        $result = self::$PayFactory->payment()
            ->common()
            ->query($order['out_trade_no']);

        self::responseChecker($result);

        return self::responseHandle($result);
    }

    /**
     * 订单查询
     * @param array $order
     * @return mixed
     */
    public static function queryRefundOrder(array $order)
    {
        if (!isset($order['out_trade_no']) || !$order['out_trade_no']) {
            throw new PayException('订单号不能为空');
        }

        if (!isset($order['out_request_no']) || !$order['out_request_no']) {
            throw new PayException('退款请求号不能为空');
        }

        $result = self::$PayFactory->payment()
            ->common()
            ->queryRefund($order['out_trade_no'], $order['out_request_no']);

        self::responseChecker($result);

        return self::responseHandle($result);
    }

    /**
     * 订单撤销
     * @param array $order
     * @return mixed
     */
    public static function cancelOrder(array $order)
    {
        if (!isset($order['out_trade_no']) || !$order['out_trade_no']) {
            throw new PayException('订单号不能为空');
        }

        $result = self::$PayFactory->payment()
            ->common()
            ->cancel($order['out_trade_no']);

        self::responseChecker($result);

        return self::responseHandle($result);
    }

    /**
     * 订单关闭
     * @param array $order
     * @return mixed
     */
    public static function closeOrder(array $order)
    {
        if (!isset($order['out_trade_no']) || !$order['out_trade_no']) {
            throw new PayException('订单号不能为空');
        }

        $result = self::$PayFactory->payment()
            ->common()
            ->close($order['out_trade_no']);

        self::responseChecker($result);

        return self::responseHandle($result);
    }


    /**
     * response 验证
     * @param $response
     * @throws PayException
     * @return bool
     */
    private static function responseChecker($response)
    {
        $responseChecker = new ResponseChecker();
        if ($responseChecker->success($response)) {
            return true;
        }
        $msg = "调用失败，原因：" . $response->msg . "，" . $response->subMsg . PHP_EOL;
        throw new PayException($msg);
    }
}
