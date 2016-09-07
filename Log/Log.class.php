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
 * Log
 * 日志抽象类
 * @author DongNan <dongyh@126.com>
 * @date 2015-9-1
 */
abstract class Log {

    /**
     * 调试日志
     */
    const DEBUG = 100;

    /**
     * 普通日志
     */
    const INFO = 200;

    /**
     * 罕见的事件日志
     */
    const NOTICE = 250;

    /**
     * 非错误的警示信息，例如过期方法的提醒或一些非错误的异常警告日志
     */
    const WARNING = 300;

    /**
     * 错误日志
     */
    const ERROR = 400;

    /**
     * 临界错误日志，系统负载过高或不同寻常的异常日志
     */
    const CRITICAL = 500;

    /**
     * 需要立即处理的错误，例如网站瘫痪，数据库不可用等
     */
    const ALERT = 550;

    /**
     * 突发事件，紧急情况日志
     */
    const EMERGENCY = 600;

    /**
     * Logging levels from syslog protocol defined in RFC 5424
     *
     * @var array $levels 日志等级
     */
    protected static $levels = [
        100 => 'DEBUG',
        200 => 'INFO',
        250 => 'NOTICE',
        300 => 'WARNING',
        400 => 'ERROR',
        500 => 'CRITICAL',
        550 => 'ALERT',
        600 => 'EMERGENCY',
    ];

    /**
     * 添加日志记录
     * @access public
     * @param int $level 日志等级
     * @param string $message 日志内容
     * @param array $data 日志相关数组数据
     * @return boolean
     */
    abstract public function log($level, $message, $data = []);

    /**
     * 添加 debug 日志
     * @access public
     * @param string $message 日志内容
     * @param array $data 日志相关数组数据
     * @return boolean
     */
    abstract public function debug($message, $data = []);

    /**
     * 添加 info 日志
     * @access public
     * @param string $message 日志内容
     * @param array $data 日志相关数组数据
     * @return boolean
     */
    abstract public function info($message, $data = []);

    /**
     * 添加 notice 日志
     * @access public
     * @param string $message 日志内容
     * @param array $data 日志相关数组数据
     * @return boolean
     */
    abstract public function notice($message, $data = []);

    /**
     * 添加 warning 日志
     * @access public
     * @param string $message 日志内容
     * @param array $data 日志相关数组数据
     * @return boolean
     */
    abstract public function warning($message, $data = []);

    /**
     * 添加 error 日志
     * @access public
     * @param string $message 日志内容
     * @param array $data 日志相关数组数据
     * @return boolean
     */
    abstract public function error($message, $data = []);

    /**
     * 添加 critical 日志
     * @access public
     * @param string $message 日志内容
     * @param array $data 日志相关数组数据
     * @return boolean
     */
    abstract public function critical($message, $data = []);

    /**
     * 添加 alert 日志
     * @access public
     * @param string $message 日志内容
     * @param array $data 日志相关数组数据
     * @return boolean
     */
    abstract public function alert($message, $data = []);

    /**
     * 添加 emergency 日志
     * @access public
     * @param string $message 日志内容
     * @param array $data 日志相关数组数据
     * @return boolean
     */
    abstract public function emergency($message, $data = []);
}
