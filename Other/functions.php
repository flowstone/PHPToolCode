<?php

/**
 * LinkTool - A useful library for PHP 
 *
 * @author      Dong Nan <hidongnan@gmail.com>
 * @copyright   (c) Dong Nan http://idongnan.cn All rights reserved.
 * @link        https://github.com/dongnan/LinkTool
 * @license     BSD (http://opensource.org/licenses/BSD-3-Clause)
 */

/**
 * 获取和设置配置参数
 * @param string|array $name 配置变量
 * @param mixed $value 配置值
 * @param mixed $default 默认值
 * @return mixed
 */
function conf($name = '', $value = null, $default = null) {
    if (is_string($name) && is_null($value)) {
        return Config::get($name, $default);
    } else {
        return Config::set($name, $value);
    }
}

/**
 * 获取语言定义
 * @param string $name  语言定义的key
 * @param array $value  需要替换的变量
 * @return mixed
 */
function lang($name = '', $value = null) {
    return Lang::get($name, $value);
}

/**
 * 添加日志记录
 * @param string $message 日志内容
 * @param int $level 日志等级
 * @param array $data 日志相关数组数据
 * @return boolean
 */
function record($message, $level = DEBUG, $data = []) {
    return Factory::Log()->log($level, $message, $data);
}

/**
 * 获取和设置全局变量 支持批量定义
 * @param string|array $name 全局变量
 * @param mixed $value 变量值
 * @param mixed $default 默认值
 * @return mixed
 */
function reg($name = null, $value = null, $default = null) {
    if (is_string($name) && is_null($value)) {
        return Register::get($name, $default);
    } else {
        return Register::set($name, $value);
    }
}

/**
 * 获取客户端IP地址
 * @param integer $type 返回类型 0 返回IP地址 1 返回IPV4地址数字
 * @param boolean $adv 是否进行高级模式获取（有可能被伪装） 
 * @return mixed
 */
function get_client_ip($type = 0, $adv = false) {
    $type = $type ? 1 : 0;
    static $ip = NULL;
    if ($ip !== NULL)
        return $ip[$type];
    if ($adv) {
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            $pos = array_search('unknown', $arr);
            if (false !== $pos)
                unset($arr[$pos]);
            $ip = trim($arr[0]);
        }elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (isset($_SERVER['REMOTE_ADDR'])) {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
    } elseif (isset($_SERVER['REMOTE_ADDR'])) {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    // IP地址合法验证
    $long = sprintf("%u", ip2long($ip));
    $ip = $long ? [$ip, $long] : ['0.0.0.0', 0];
    return $ip[$type];
}

/**
 * 发送HTTP状态
 * @param integer $code 状态码
 * @return void
 */
function send_http_status($code) {
    static $_status = [
        // Informational 1xx
        100 => 'Continue',
        101 => 'Switching Protocols',
        // Success 2xx
        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        203 => 'Non-Authoritative Information',
        204 => 'No Content',
        205 => 'Reset Content',
        206 => 'Partial Content',
        // Redirection 3xx
        300 => 'Multiple Choices',
        301 => 'Moved Permanently',
        302 => 'Moved Temporarily ', // 1.1
        303 => 'See Other',
        304 => 'Not Modified',
        305 => 'Use Proxy',
        // 306 is deprecated but reserved
        307 => 'Temporary Redirect',
        // Client Error 4xx
        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        407 => 'Proxy Authentication Required',
        408 => 'Request Timeout',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition Failed',
        413 => 'Request Entity Too Large',
        414 => 'Request-URI Too Long',
        415 => 'Unsupported Media Type',
        416 => 'Requested Range Not Satisfiable',
        417 => 'Expectation Failed',
        // Server Error 5xx
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Timeout',
        505 => 'HTTP Version Not Supported',
        509 => 'Bandwidth Limit Exceeded'
    ];
    if (isset($_status[$code])) {
        header('HTTP/1.1 ' . $code . ' ' . $_status[$code]);
        // 确保FastCGI模式下正常
        header('Status:' . $code . ' ' . $_status[$code]);
    }
}

/**
 * 不区分大小写的in_array实现
 * @param string $value
 * @param array $array
 * @return boolean
 */
function in_array_case($value, $array) {
    return in_array(strtolower($value), array_map('strtolower', $array));
}

/**
 * 递归的将回调函数作用到给定数组的单元上
 * @param callable $filter
 * @param array $data
 * @return array
 */
function array_map_recursive($filter, $data) {
    $result = [];
    foreach ($data as $key => $val) {
        $result[$key] = is_array($val) ? array_map_recursive($filter, $val) : call_user_func($filter, $val);
    }
    return $result;
}

/**
 * 返回字符串键名全为小写或大写的数组(递归)
 * @param array $arr
 * @param int $case CASE_LOWER|CASE_UPPER
 * @return array
 */
function array_change_key_case_recursive($arr, $case = CASE_LOWER) {
    return array_map(function($item) use ($case) {
        if (is_array($item)) {
            $item = array_change_key_case_recursive($item, $case);
        }
        return $item;
    }, array_change_key_case($arr, $case));
}

/**
 * 区分大小写的文件存在判断
 * @param string $filename 文件地址
 * @return boolean
 */
function file_exists_case($filename) {
    if (is_file($filename)) {
        if (IS_WIN && APP_DEBUG) {
            if (basename(realpath($filename)) != basename($filename))
                return false;
        }
        return true;
    }
    return false;
}

/**
 * 判断是否SSL协议
 * @return boolean
 */
function is_ssl() {
    if (isset($_SERVER['HTTPS']) && ('1' == $_SERVER['HTTPS'] || 'on' == strtolower($_SERVER['HTTPS']))) {
        return true;
    } elseif (isset($_SERVER['SERVER_PORT']) && ('443' == $_SERVER['SERVER_PORT'] )) {
        return true;
    }
    return false;
}

/**
 * URL重定向
 * @param string $url 重定向的URL地址
 * @param integer $time 重定向的等待时间（秒）
 * @param string $msg 重定向前的提示信息
 * @return void
 */
function redirect($url, $time = 0, $msg = '') {
    //多行URL地址支持
    $url = str_replace(["\n", "\r"], '', $url);
    if (empty($msg))
        $msg = "系统将在{$time}秒之后自动跳转到{$url}！";
    if (!headers_sent()) {
        // redirect
        if (0 === $time) {
            header('Location: ' . $url);
        } else {
            header("refresh:{$time};url={$url}");
            echo($msg);
        }
        exit();
    } else {
        $str = "<meta http-equiv='Refresh' content='{$time};URL={$url}'>";
        if ($time != 0)
            $str .= $msg;
        exit($str);
    }
}

/**
 * 根据PHP各种类型变量生成唯一标识号
 * @param mixed $mix 变量
 * @return string
 */
function to_guid_string($mix) {
    if (is_object($mix)) {
        return spl_object_hash($mix);
    } elseif (is_resource($mix)) {
        $mix = get_resource_type($mix) . strval($mix);
    } else {
        $mix = serialize($mix);
    }
    return md5($mix);
}

/**
 * 生成css html标签
 * @param string $path
 * @param string $site
 * @param string $version
 * @return string
 */
function tag_css($path, $site = 'asset', $version = '1.0') {
    if (strpos($path, '/') !== 0) {
        $path = '/' . $path;
    }
    if (!empty($site)) {
        $path = conf('SITE.' . $site) . $path;
    }
    return '<link href="' . $path . '?v=' . $version . '" rel="stylesheet" type="text/css" />' . PHP_EOL;
}

/**
 * 生成js html标签
 * @param string $path
 * @param string $site
 * @param string $version
 * @return string
 */
function tag_js($path, $site = 'asset', $version = '1.0') {
    if (strpos($path, '/') !== 0) {
        $path = '/' . $path;
    }
    if (!empty($site)) {
        $path = conf('SITE.' . $site) . $path;
    }
    return '<script src="' . $path . '?v=' . $version . '" type="text/javascript"></script>' . PHP_EOL;
}

/**
 * 过滤查询特殊字符
 * @param string $value
 */
function lt_filter(&$value) {
    // TODO 其他安全过滤
    // 过滤查询特殊字符
    if (preg_match('/^(EXP|NEQ|GT|EGT|LT|ELT|OR|XOR|LIKE|NOTLIKE|NOT BETWEEN|NOTBETWEEN|BETWEEN|NOTIN|NOT IN|IN)$/i', $value)) {
        $value .= ' ';
    }
}
