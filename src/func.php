<?php

/**
 * 通过 google api 生成二维码图片
 * @param $content
 * @param string|int $size
 * @param string $lev
 * @param string|int $margin
 * @return string
 */
function createErweima($content, $size = '200', $lev = 'L', $margin = '0')
{
    $content = urlencode($content);
    $url = "http://chart.apis.google.com/chart?chs=$size" . 'x' . "$size&amp;cht=qr&chld=$lev|$margin&amp;chl=$content";
    // $image = '<img src="http://chart.apis.google.com/chart?chs=' . $size . 'x' . $size . '&amp;cht=qr&chld=' . $lev . '|' . $margin . '&amp;chl=' . $content . '"  widht="' . $size . '" height="' . $size . '" />';
    return $url;
}

/**
 * 类名转大驼峰
 * @param $className
 * @return string
 */
function classNameToHump(string $name)
{
    $words = explode('_', $name);
    return implode('', array_map(function ($word) {
        return ucfirst($word);
    }, $words));
}

/**
 * 获取客户端IP地址
 * 
 */
function getIp()
{ //取IP函数
    static $realip;
    if (isset($_SERVER)) {
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $realip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $realip = isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : $_SERVER['REMOTE_ADDR'];
        }
    } else {
        if (getenv('HTTP_X_FORWARDED_FOR')) {
            $realip = getenv('HTTP_X_FORWARDED_FOR');
        } else {
            $realip = getenv('HTTP_CLIENT_IP') ? getenv('HTTP_CLIENT_IP') : getenv('REMOTE_ADDR');
        }
    }
    $realip = explode(",", $realip);

    return $realip[0];
}

//数组拼接为url参数形式
function urlparams(array $params)
{
    return implode('&', array_map(function ($key, $value) {
        return $key . '=' . $value;
    }, array_keys($params), $params));
}

/* PHP CURL HTTPS POST */
function curl_post_https($url, $data)
{ // 模拟提交数据函数
    $curl = curl_init(); // 启动一个CURL会话
    curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
    curl_setopt($curl, CURLOPT_URL, $url); // 要访问的地址
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0); // 对认证证书来源的检查
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2); // 从证书中检查SSL加密算法是否存在
    curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']); // 模拟用户使用的浏览器
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1); // 使用自动跳转
    curl_setopt($curl, CURLOPT_AUTOREFERER, 1); // 自动设置Referer
    curl_setopt($curl, CURLOPT_POST, 1); // 发送一个常规的Post请求
    curl_setopt($curl, CURLOPT_POSTFIELDS, $data); // Post提交的数据包
    curl_setopt($curl, CURLOPT_TIMEOUT, 30); // 设置超时限制防止死循环
    curl_setopt($curl, CURLOPT_HEADER, 0); // 显示返回的Header区域内容
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); // 获取的信息以文件流的形式返回
    $tmpInfo = curl_exec($curl); // 执行操作
    if (curl_errno($curl)) {
        echo 'Errno' . curl_error($curl); //捕抓异常
    }
    curl_close($curl); // 关闭CURL会话
    return $tmpInfo; // 返回数据，json格式
}

function parseurl($url = "")
{
    $url = rawurlencode(mb_convert_encoding($url, 'gb2312', 'utf-8'));
    $a = array("%3A", "%2F", "%40");
    $b = array(":", "/", "@");
    $url = str_replace($a, $b, $url);
    return $url;
}
