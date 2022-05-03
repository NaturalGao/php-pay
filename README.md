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
