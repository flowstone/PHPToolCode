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
 * Request
 * Request工具类
 * 
 * @author Dong Nan <hidongnan@gmail.com>
 * @date 2015-9-6
 */
class Request {

    /** 将不确定类型的数据自动转换为字符串 */
    static public $AUTO_STRING = true;

    /** 默认过滤数据的函数名 */
    static public $FILTER = '';

    /** $_GET 参数 */
    const GET = INPUT_GET;

    /** $_POST 参数 */
    const POST = INPUT_POST;

    /** PUT 方式输入参数 */
    const PUT = 3;

    /** $_REQUEST 参数 */
    const REQUEST = INPUT_REQUEST;

    /** $_SERVER 参数 */
    const SERVER = INPUT_SERVER;

    /** $_ENV 参数 */
    const ENV = INPUT_ENV;

    /** $_COOKIE 参数 */
    const COOKIE = INPUT_COOKIE;

    /** $_SESSION 参数 */
    const SESSION = INPUT_SESSION;

    /** $GLOBALS 参数 */
    const GLOBALS = 7;

    static private $_PUT = null;
    static private $_defaultFilters = null;

    /**
     * 获取 $_GET 参数 支持过滤和默认值
     * @param string $name 变量的名称
     * @param mixed $default 不存在的时候默认值
     * @param mixed $filter 参数过滤方法
     * @param mixed $options 参数过滤附加参数
     * @return mixed
     */
    static public function get($name = '', $default = '', $filter = null, $options = null) {
        return self::input(self::GET, $name, $default, $filter, $options);
    }

    /**
     * 获取 $_POST 参数 支持过滤和默认值
     * @param string $name 变量的名称
     * @param mixed $default 不存在的时候默认值
     * @param mixed $filter 参数过滤方法
     * @param mixed $options 参数过滤附加参数
     * @return mixed
     */
    static public function post($name = '', $default = '', $filter = null, $options = null) {
        return self::input(self::POST, $name, $default, $filter, $options);
    }

    /**
     * 获取 PUT 方式输入参数 支持过滤和默认值
     * @param string $name 变量的名称
     * @param mixed $default 不存在的时候默认值
     * @param mixed $filter 参数过滤方法
     * @param mixed $options 参数过滤附加参数
     * @return mixed
     */
    static public function put($name = '', $default = '', $filter = null, $options = null) {
        return self::input(self::PUT, $name, $default, $filter, $options);
    }

    /**
     * 获取 $_REQUEST 参数 支持过滤和默认值
     * @param string $name 变量的名称
     * @param mixed $default 不存在的时候默认值
     * @param mixed $filter 参数过滤方法
     * @param mixed $options 参数过滤附加参数
     * @return mixed
     */
    static public function request($name = '', $default = '', $filter = null, $options = null) {
        return self::input(self::REQUEST, $name, $default, $filter, $options);
    }

    /**
     * 获取 $_SERVER 参数 支持过滤和默认值
     * @param string $name 变量的名称
     * @param mixed $default 不存在的时候默认值
     * @param mixed $filter 参数过滤方法
     * @param mixed $options 参数过滤附加参数
     * @return mixed
     */
    static public function server($name = '', $default = '', $filter = null, $options = null) {
        return self::input(self::SERVER, $name, $default, $filter, $options);
    }

    /**
     * 获取 $_ENV 参数 支持过滤和默认值
     * @param string $name 变量的名称
     * @param mixed $default 不存在的时候默认值
     * @param mixed $filter 参数过滤方法
     * @param mixed $options 参数过滤附加参数
     * @return mixed
     */
    static public function env($name = '', $default = '', $filter = null, $options = null) {
        return self::input(self::ENV, $name, $default, $filter, $options);
    }

    /**
     * 获取 $_COOKIE 参数 支持过滤和默认值
     * @param string $name 变量的名称
     * @param mixed $default 不存在的时候默认值
     * @param mixed $filter 参数过滤方法
     * @param mixed $options 参数过滤附加参数
     * @return mixed
     */
    static public function cookie($name = '', $default = '', $filter = null, $options = null) {
        return self::input(self::COOKIE, $name, $default, $filter, $options);
    }

    /**
     * 获取 $_SESSION 参数 支持过滤和默认值
     * @param string $name 变量的名称
     * @param mixed $default 不存在的时候默认值
     * @param mixed $filter 参数过滤方法
     * @param mixed $options 参数过滤附加参数
     * @return mixed
     */
    static public function session($name = '', $default = '', $filter = null, $options = null) {
        return self::input(self::SESSION, $name, $default, $filter, $options);
    }

    /**
     * 获取 $GLOBALS 参数 支持过滤和默认值
     * @param string $name 变量的名称
     * @param mixed $default 不存在的时候默认值
     * @param mixed $filter 参数过滤方法
     * @param mixed $options 参数过滤附加参数
     * @return mixed
     */
    static public function globals($name = '', $default = '', $filter = null, $options = null) {
        return self::input(self::GLOBALS, $name, $default, $filter, $options);
    }

    /**
     * 获取输入参数 支持过滤和默认值
     * @param int $type 输入参数类型
     * @param string $name 变量的名称
     * @param mixed $default 不存在的时候默认值
     * @param mixed $filter 参数过滤方法
     * @param mixed $options 参数过滤附加参数
     * @return type
     */
    static private function input($type, $name = '', $default = '', $filter = null, $options = null) {
        if ($type === self::PUT) {
            if (is_null(self::$_PUT)) {
                parse_str(file_get_contents('php://input'), self::$_PUT);
            }
            $value = & self::$_PUT;
        } elseif ($type === self::REQUEST) {
            $value = & $_REQUEST;
        } elseif ($type === self::SESSION) {
            $value = & $_SESSION;
        } elseif ($type === self::GLOBALS) {
            $value = & $GLOBALS;
        } else {
            $value = filter_input_array($type);
        }
        if (trim(strval($name)) === '') {
            return self::filterArr($value, $filter);
        } else {
            if (strpos($name, '/')) { // 指定修饰符
                list($name, $valType) = explode('/', $name, 2);
            } elseif (self::$AUTO_STRING) { // 默认强制转换为字符串
                $valType = 's';
            }
            $value = isset($value[$name]) ? $value[$name] : $default;
            return self::filter($value, $valType, $default, $filter, $options);
        }
    }

    /**
     * 过滤参数
     * @param mixed $value
     * @param string $type
     * @param mixed $default
     * @param mixed $filter
     * @param mixed $options
     * @return mixed
     */
    static private function filter($value, $type = null, $default = '', $filter = null, $options = null) {
        //获取系统默认的过滤器
        if (!isset(self::$_defaultFilters)) {
            self::$_defaultFilters = filter_list();
        }
        // 默认强制转换为字符串
        if (is_null($type) && self::$AUTO_STRING) {
            $type = 's';
        }
        $filters = isset($filter) ? $filter : self::$FILTER;
        if ($filters) {
            if (is_string($filters)) {
                if (0 === strpos($filters, '/')) {
                    if (1 !== preg_match($filters, (string) $value)) {
                        // 支持正则验证
                        return isset($default) ? $default : null;
                    }
                } elseif (strpos($filters, ',')) {
                    $filters = explode(',', $filters);
                } else {
                    $filters = [$filters];
                }
            } elseif (is_int($filters)) {
                $filters = [$filters];
            }
            if (is_array($filters)) {
                foreach ($filters as $filter) {
                    if (function_exists($filter)) {
                        $value = is_array($value) ? array_map_recursive($filter, $value) : $filter($value); // 参数过滤
                    } else {
                        if (is_int($filter)) {
                            $filter = isset(self::$_defaultFilters[$filter]) ? $filter : null;
                        } else {
                            $filter = filter_id($filter)? : null;
                        }
                        $value = filter_var($value, $filter);
                        if (false === $value) {
                            return isset($default) ? $default : null;
                        }
                    }
                }
            }
        }
        if (!empty($type)) {
            switch (strtolower($type)) {
                case 'a': // 数组
                    $value = (array) $value;
                    break;
                case 'd': // 数字
                    $value = (int) $value;
                    break;
                case 'f': // 浮点
                    $value = (float) $value;
                    break;
                case 'b': // 布尔
                    $value = (boolean) $value;
                    break;
                case 's':   // 字符串
                default:
                    $value = (string) $value;
            }
        }
        return $value;
    }

    /**
     * 过滤数组参数
     * @param array $value
     * @param mixed $filter
     * @return mixed
     */
    static private function filterArr($value, $filter = null) {
        $filters = isset($filter) ? $filter : self::$FILTER;
        if ($filters) {
            if (is_array($filters)) {
                return filter_var_array($value, $filter);
            }
            if (is_string($filters)) {
                if (strpos($filters, ',')) {
                    $filters = explode(',', $filters);
                } else {
                    $filters = [$filters];
                }
            } elseif (is_int($filters)) {
                $filters = [$filters];
            }
            if (is_array($filters)) {
                foreach ($filters as $filter) {
                    if (function_exists($filter)) {
                        $value = is_array($value) ? array_map_recursive($filter, $value) : $filter($value); // 参数过滤
                    } else {
                        if (is_int($filter)) {
                            $filter = isset(self::$_defaultFilters[$filter]) ? $filter : null;
                        } else {
                            $filter = filter_id($filter)? : null;
                        }
                        $value = filter_var($value, $filter);
                    }
                }
            }
        }
        return $value;
    }

}
