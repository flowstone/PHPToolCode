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
 * String
 *
 * @author Dong Nan <hidongnan@gmail.com>
 * @date 2015-8-23
 */
class String {

    /**
     * 过滤非法字符
     * @param   string  $str         <p>需要过滤的字符串</p>
     * @param   array   $filterwords <p>包含非法字符串的数组</p>
     * @param   int     $size        <p>将非法字符串的数组分块处理的大小</p>
     * @return  string               <p>返回匹配的第一个非法字符串</p>
     */
    public static function filter($str, $filterwords, $size = 100) {
        $stopword = '';
        $pattern = '';
        if (is_array($filterwords) && !empty($filterwords)) {
            $wordsArr = array_chunk($filterwords, $size);
            foreach ($wordsArr as $row) {
                $matches = [];
                $pattern = implode("|", $row);
                if (preg_match("/{$pattern}/i", $str, $matches)) {
                    $stopword = $matches[0];
                    break;
                }
            }
        }
        return $stopword;
    }

    /**
     * 获取UTF-8格式的字符串长度
     * @param   string  $str  <p>待检测的字符串</p>
     * @return  int           <p>字符串<i>str</i>的长度，其中多字节的字符计1个长度</p>
     */
    public static function strlenUtf8($str) {
        if (function_exists('mb_strlen')) {
            return mb_strlen($str, 'utf-8');
        } else {
            $ar = [];
            preg_match_all("/./u", $str, $ar);
            return count($ar[0]);
        }
    }

    /**
     * 获取一个随机字符串(排除了易混淆的字符)
     * @param   int     $length  <p>要获取的字符串的长度</p>
     * @return  string           <p>返回的随机字符串</p>
     */
    public static function randStr($length = 6) {
        $str = '';
        $chars = 'ABDEFGHJKLMNPQRSTVWXYabdefghijkmnpqrstvwxy23456789';
        $randLen = strlen($chars) - 1;
        for ($i = 0; $i < $length; ++$i) {
            $str.=substr($chars, rand(0, $randLen), 1);
        }
        return $str;
    }

    /**
     * 以UTF-8格式截取字符串
     * @param string $str     <p>待截取的字符串</p>
     * @param int    $start   <p>从字符串<i>str</i>开始截取的位置</p>
     * @param int    $length  <p>从字符串<i>str</i>截取的长度，如果超长或为NULL,将返回相同的字符串</p>
     * @return string
     */
    public static function substrUtf8($str, $start, $length = null) {
        if (empty($length)) {
            return $str;
        }
        if (function_exists('mb_substr')) {
            return mb_substr($str, $start, $length, 'UTF-8');
        } else {
            $matches = [];
            preg_match_all("/./su", $str, $matches);
            return join("", array_slice($matches[0], $start, $length));
        }
    }

    /**
     * 移除所有不可见字符
     * @param string $string    <p>待处理的字符串</p>
     * @return string           <p>处理后的字符串</p>
     */
    public static function trimInvisible($string) {
        $newstr = '';
        $length = strlen($string);
        for ($i = 0; $i < $length; $i++) {
            $ascii = ord($string[$i]);
            //不可见字符
            if ($ascii <= 31 || $ascii == 127) {
                continue;
            }
            $newstr .= $string[$i];
        }
        return $newstr;
    }

    /**
     * 替换字符串
     * @param string $search    <p>需要被替换的字符串</p>
     * @param string $replace   <p>替换的字符串</p>
     * @param mixed $data       <p>要处理的数据，可以是字符串或包含字符串的数组</p>
     * @return mixed            <p>处理后的数据</p>
     */
    static public function replace($search, $replace, $data) {
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                $data[$key] = self::replace($search, $replace, $value);
            }
        } elseif (is_string($data)) {
            $data = str_replace($search, $replace, $data);
        }
        return $data;
    }

}
