<?php

/**
 *
 * @author      Dong Nan <hidongnan@gmail.com>
 * @copyright   (c) Dong Nan http://idongnan.cn All rights reserved.
 * @link        https://github.com/dongnan/LinkTool
 * @license     BSD (http://opensource.org/licenses/BSD-3-Clause)
 */



/**
 * Config
 * 配置工具类，配置可以定义为多维数组
 *
 * @author Dong Nan <hidongnan@gmail.com>
 * @date 2015-8-28
 */
class Config {

    static private $config = [];

    /**
     * 获取配置
     * @param string $name
     * @param mixed $default
     * @return mixed
     */
    static public function get($name = '', $default = null) {
        // 无参数时获取所有
        if (empty($name)) {
            return self::$config;
        }
        // 优先执行设置获取或赋值
        if (is_string($name)) {
            if (!strpos($name, '.')) {
                $name = strtoupper($name);
                return isset(self::$config[$name]) ? self::$config[$name] : $default;
            }
            // 支持多维数组获取
            $name = explode('.', $name);
            $first = strtoupper(trim(array_shift($name)));
            if (isset(self::$config[$first])) {
                return self::getRecursive(self::$config[$first], $name, $default);
            } else {
                return $default;
            }
        }
        return null;
    }

    /**
     * 递归的获取配置
     * @param array $config
     * @param array $names
     * @param mixed $default
     * @return mixed
     */
    static private function getRecursive($config, $names, $default = null) {
        if (empty($names)) {
            return $config;
        } else {
            $name = trim(array_shift($names));
            if ($name) {
                if (isset($config[$name])) {
                    return self::getRecursive($config[$name], $names, $default);
                } else {
                    return $default;
                }
            } else {
                return $config;
            }
        }
    }

    /**
     * 设置配置
     * @param mixed $name
     * @param mixed $value
     * @return boolean
     */
    static public function set($name, $value = null) {
        // 配置定义
        if (is_string($name)) {
            $name = strtoupper($name);
            self::$config[$name] = $value;
            return true;
        }
        // 批量定义
        elseif (is_array($name)) {
            self::$config = array_merge(self::$config, array_change_key_case($name, CASE_UPPER));
            return true;
        }
        return false;
    }

    /**
     * 根据文件载入配置
     * @param string $file
     * @return boolean
     */
    static public function load($file) {
        $ext = pathinfo($file, PATHINFO_EXTENSION);
        switch ($ext) {
            case 'php':
                return self::set(include $file);
            case 'ini':
                return self::set(parse_ini_file($file));
            case 'xml':
                return self::set((array) simplexml_load_file($file));
            case 'json':
                return self::set(json_decode(file_get_contents($file), true));
        }
        return false;
    }

}
