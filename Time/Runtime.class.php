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
 * Runtime
 * 运行时运行时间、内存等信息统计
 * 
 * @author Dong Nan <hidongnan@gmail.com>
 * @date 2015-8-29
 */
class Runtime {

    static private $time = [];
    static private $memory = [];

    /**
     * 记录当前运行时间和内存
     * @param string $tag
     * @return boolean
     */
    static public function mark($tag) {
        if (is_string($tag)) {
            // 记录时间和内存使用
            self::$time[$tag] = microtime(TRUE);
            if (LT_MEMORY_ON) {
                self::$memory[$tag] = memory_get_usage();
            }
            return true;
        } else {
            return false;
        }
    }

    /**
     * 获取记录的运行时间和内存
     * @param string $tag       开始标记
     * @param string $endTag    结束标记,如果之前没有记录结束标记状态，则在此记录
     * @return array|boolean
     */
    static public function get($tag, $endTag = '') {
        $result = [];
        if (empty($endTag)) {
            $result['time'] = isset(self::$time[$tag]) ? self::$time[$tag] : null;
            if (LT_MEMORY_ON) {
                $result['memory'] = isset(self::$memory[$tag]) ? self::$memory[$tag] : null;
            }
        } else {
            if (empty(self::$time[$tag]) || (LT_MEMORY_ON && empty(self::$memory[$tag]))) {
                return false;
            } else {
                if (empty(self::$time[$endTag])) {
                    self::mark($endTag);
                }
                $result['time_begin'] = self::$time[$tag];
                $result['time_end'] = self::$time[$endTag];
                $result['time'] = $result['time_end'] - $result['time_begin'];
                if (LT_MEMORY_ON) {
                    $result['memory_begin'] = self::$memory[$tag];
                    $result['memory_end'] = self::$memory[$endTag];
                    $result['memory'] = $result['memory_end'] - $result['memory_begin'];
                }
            }
        }
        return $result;
    }

}
