[![Packagist](https://poser.pugx.org/alipaysdk/easysdk/v/stable)](https://packagist.org/packages/natural-gao/php-pay)

# PHP-PAY

---

## 项目简介

一个借助第三方包整合的支付工具，目前整合了 微信支付、支付宝支付、优云宝。

## 项目由来

工作业务中有这方面需求，目前是借助第三方包整合的，会有点臃肿，有时间会花时间重构，让项目专注于支付，更轻量。

## 项目进度

因业务中要求的功能不多，目前只整合了微信 Native 支付、支付宝扫码支付、优云宝，后面会慢慢完善更多功能。

## 安装

```ssh
composer require natural-gao/php-pay
```

## 快速使用

创建一个支付宝扫码支付为例：

```php
use NaturalGao\PhpPay\Factory;
// 初始化配置
第一个参数是 引擎名称（根据服务文件前缀，例如 AliPayService.php 就是 ali, YouYunPayService.php 就是 you_yun ）；
第二个参数是 配置
Factory::init('ali', $config);

// 创建下单
$response = Factory::service()->createQrCodeOrder($order);
```

返回数据格式:

```json
{
    "type": "alipay",
    "data": "http://chart.apis.google.com/chart?chs=200x200&cht=qr&chld=L|0&chl=https%3A%2F%2Fqr.alipay.com%2Fbax08192saiuyc9fpxo0000b"
}
```

**type 是使用的支付类型, data 是返回的数据，可以是任意类型数据，实际根据支付官网为准。**

## 配置

支付宝

```php
    $config = [
        'protocol' => 'https',
        // 网关
        'gatewayHost' => 'openapi.alipay.com',
        // 签名模式
        'signType' => 'RSA2',
        // APPID
        'appId' => '',
        // 私钥
        'merchantPrivateKey' => '',
        // 支付宝公钥证书文件路径 （证书模式必填）
        'alipayCertPath' => '',
        // 支付宝根证书文件路径（证书模式必填）
        'alipayRootCertPath' => '',
        // 应用公钥证书文件路径（证书模式必填）
        'merchantCertPath' => '',
        // 如果采用非证书模式，则无需赋值上面的三个证书路径，改为赋值如下的支付宝公钥字符串即可
        'alipayPublicKey' => '',
        //可设置异步通知接收服务地址（可选）
        'notifyUrl' => '',
        // 可设置AES密钥，调用AES加解密相关接口时需要（可选）
        'encryptKey' => ''
    ];
```

微信

```php
$config = [
    'mch_id' => 1360649000,

    // 商户证书
    'private_key' => '',
    'certificate' => '',

     // v3 API 秘钥
    'secret_key' => '',

    // v2 API 秘钥
    'v2_secret_key' => '',

    // 平台证书：微信支付 APIv3 平台证书，需要使用工具下载
    // 下载工具：https://github.com/wechatpay-apiv3/CertificateDownloader
    'platform_certs' => [
        // '/path/to/wechatpay/cert.pem',
    ],

    /**
     * 接口请求相关配置，超时时间等，具体可用参数请参考：
     * https://github.com/symfony/symfony/blob/5.3/src/Symfony/Contracts/HttpClient/HttpClientInterface.php
     */
    'http' => [
        'throw'  => true, // 状态码非 200、300 时是否抛出异常，默认为开启
        'timeout' => 5.0,
        // 'base_uri' => 'https://api.mch.weixin.qq.com/', // 如果你在国外想要覆盖默认的 url 的时候才使用，根据不同的模块配置不同的 uri
    ],
];

```

优云宝

```php
$congig = [
   //收款APPID号
   "appid"=>"22222",

   //对应的APPKEY密匙
   "appkey"=>"22222",

   //网关连接地址 一般不做修改
   "server"=>"http://yunpay.youyunnet.com/",   //注意：最后要加斜杠 /

   //支付成功后的跳转地址
   "reurl"=>"http://".$_SERVER['HTTP_HOST']."/",
   //默认当前域名,可根据自己的需求自己开发
   //如果跳转需要带参数 请在AJAX页面自行组合并添加，这个只是一个返回效果并无数据返回
   //请用户不要误认为是异步数据通知的链接

   //获取客户IP(必须)
   "uip"=>getIp(),

   //模板提示支付帮助 1提示 0不提示
   "helpts"=>1 ,

   "alipayh5"=>0 // 0 1是否开启自动生成二维码，开启后云端上传的二维码将失效

];
```

## 接口

| 名称              | 描述           | 支持情况                         |
| ----------------- | -------------- | -------------------------------- |
| createOrder       | 创建订单       | 支付宝                           |
| createQrCodeOrder | 创建二维码订单 | 支付宝、微信、优云宝             |
| refundOrder       | 订单退款       | 支付宝、微信                     |
| queryOrder        | 查询订单       | 支付宝、微信（商户订单）、优云宝 |
| queryWechatOrder  | 查询微信订单   | 微信（微信订单）                 |
| queryRefundOrder  | 查询退款订单   | 支付宝、微信                     |
| closeOrder        | 关闭订单       | 支付宝、微信                     |
| cancelOrder       | 撤销订单       | 支付宝                           |

## 使用到的包

-   [easywechat
    ](https://github.com/w7corp/easywechat)

*   [alipay-easysdk](https://github.com/alipay/alipay-easysdk)
