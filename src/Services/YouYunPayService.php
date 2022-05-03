<?php

namespace NaturalGao\PhpPay\Services;

use NaturalGao\PhpPay\Exception\PayException;
use NaturalGao\PhpPay\Interface\ThirdPartyPayInterface;
use NaturalGao\PhpPay\PayService;

class YouYunPayService extends PayService implements ThirdPartyPayInterface
{
    public static $ServiceNmae = 'you_yun';

    public static $Config;

    public function __construct(array $config)
    {
        self::$Config = $config;
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
        if (!isset($order['out_trade_no']) || !$order['out_trade_no']) {
            throw new PayException('订单号不能为空');
        }

        if (!isset($order['money']) || !$order['money']) {
            throw new PayException('订单金额不能为空');
        }

        if (!isset($order['type']) || !$order['type']) {
            throw new PayException('订单类型不能为空');
        }

        $congig = self::$Config;

        $yundata = [
            "appid"  => $congig['appid'],
            "data"   => $order['out_trade_no'], //网站订单号/或者账号
            "money"  => number_format($order['money'], 2, ".", ""), //注意金额一定要格式化否则token会出现错误
            "type"   => (int) $order['type'],
            "uip"    => $congig['uip'],
        ];

        $token = self::getToken($yundata);

        $postdata = urlparams($yundata) . '&token=' . $token;

        if ($congig['alipayh5'] == 1  && $yundata["type"] == 1) { //仅限支付宝
            //启用本地备注模式
            $h5yundata = array("appid" => $congig['appid'], "data" => $yundata['data'], "money" => $yundata['money'], "atype" => 1, "type" => 1);
            $h5token = array("appid" => $congig['appid'], "data" => $yundata['data'], "money" => $yundata['money'], "type" => 1, "appkey" => $congig['appkey']);
            $h5token = md5(urlparams($h5token));
            $h5postdata = urlparams($h5yundata) . '&token=' . $h5token;
            $h5fdata = curl_post_https($congig['server'] . 'Alipay', $h5postdata);
            $h5sdata = json_decode($h5fdata, true); //将json代码转换为数组
            if ($h5sdata['state'] == 0) {
                exit($h5sdata['text']);
            }
            $h5sdata = $h5sdata['text'];
            //支付宝本地二维码模式1
            $qrcode = 'alipays://platformapi/startapp?appId=20000123&actionType=scan&biz_data={"s":"money","u": "' . $h5sdata['alipayid'] . '","a": "' . $h5sdata['money'] . '","m":"' . $h5sdata['data'] . '"}';
            //支付宝本地二维码模式2
            //$qrcode ='alipays://platformapi/startapp?appId=09999988&actionType=toAccount&goBack=NO&amount='.$h5sdata['money'].'&userId='.$h5sdata['alipayid'].'&memo='.$h5sdata['data'].'';


            $order_data = base64_encode($yundata["data"] . ',' . $yundata["money"]); //将数据进行base64编码
            $qrcode2 = '/phpdemo/alipayh5.php?data=' . $order_data . ''; //本地自动生成二维码地址
            $sdata = array('state' => 1, 'qrcode' => $qrcode, 'youorder' => $yundata["data"], 'data' => $yundata["data"], 'money' => $yundata["money"], 'times' => time() + 300, 'orderstatus' => 0, 'text' => 10089); //本地生成二维码可手动伪造JSON数据
        } else {
            //否则走云端
            $fdata = curl_post_https($congig['server'], $postdata); //发送数据到网关
            $sdata = json_decode($fdata, true); //将json代码转换为数组
        }

        if (!$sdata['state']) {
            throw new PayException('发生错误，状态码：' . $sdata['text']);
        }

        $sdata["qrcode"] = createErweima($sdata["qrcode"]);

        return self::responseHandle($sdata);
    }

    /**
     * 查询订单
     * @param $order
     * @return mixed
     */
    public static function queryOrder(array $order)
    {
        if (!isset($order['out_trade_no']) || !$order['out_trade_no']) {
            throw new PayException('订单号不能为空');
        }

        if (!isset($order['type']) || !$order['type']) {
            throw new PayException('订单类型不能为空');
        }

        $congig = self::$Config;

        $yundata = array(
            "appid"  => $congig['appid'], //获取appid
            "order"  => $order['out_trade_no'], //这个是云端返回的一个唯一单号
            "type"   => $order['type'], //获取分类
            "uip"    => $congig['uip'] //获取用户IP地址
        );

        $token = self::getToken($yundata, ['order']);

        $postdata = urlparams($yundata) . '&token=' . $token;

        //订单查询网关地址后面加 order
        $fdata = curl_post_https($congig['server'] . 'order', $postdata);

        return self::responseHandle(json_decode($fdata, true));
    }

    /**
     * 订单退款
     */
    public static function refundOrder(array $order)
    {
        self::serviceUndeDevelopment();
    }

    /**
     * 查询退款订单
     */
    public static function queryRefundOrder(array $order)
    {
        self::serviceUndeDevelopment();
    }

    // 关闭订单
    public static function closeOrder(array $order)
    {
        self::serviceUndeDevelopment();
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

    /**
     * 获取 token
     * @param $data
     * @return string
     */
    private static function getToken(array $data, array $execKey = [])
    {
        foreach ($execKey as $key) {
            unset($data[$key]);
        }
        $data['appkey'] = self::$Config['appkey'];
        return md5(urlparams($data));
    }
}
