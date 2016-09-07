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
 * DateTime
 * 用于处理日期、时间相关的方法
 *
 * @author Dong Nan <hidongnan@gmail.com>
 * @date 2016-5-10
 */
class DateTime {

    /**
     * 获取标准的日期格式:yyyy-mm-dd
     * 
     * @param string $date
     * @return string
     */
    public static function getStandardDate($date) {
        $year = 0;
        $month = 0;
        $day = 0;
        $match = preg_split('/[\D]0*/', trim($date));
        if (empty($match)) {
            return '0000-00-00';
        }
        $dateStr = '';
        $matchLen = count($match);
        for ($i = 0; $i < $matchLen; $i++) {
            $dateStr .= $match[$i] < 10 ? '0' . $match[$i] : $match[$i];
        }
        $len = strlen($dateStr);
        if ($len <= 4) {
            $year = intval($dateStr);
        } else {
            $year = intval(substr($dateStr, 0, 4));
            if ($len === 5) {
                $month = intval(substr($dateStr, 4, 1));
            } else if ($len === 6 || $len === 7) {
                if (intval(substr($dateStr, 4, 2)) <= 12) {
                    $month = intval(substr($dateStr, 4, 2));
                    $day = intval(substr($dateStr, 6, $len - 6));
                } else {
                    $month = intval(substr($dateStr, 4, 1));
                    $day = intval(substr($dateStr, 5, $len - 5));
                }
            } else {
                $year = intval(substr($dateStr, 0, 4));
                $month = intval(substr($dateStr, 4, 2));
                $day = intval(substr($dateStr, 6, 2));
            }
        }
        switch ($month) {
            case 1:
            case 3:
            case 5:
            case 7:
            case 8:
            case 10:
            case 12:
                $day > 31 && ($day = 0);
                break;
            case 4:
            case 6:
            case 9:
            case 11:
                $day > 30 && ($day = 0);
                break;
            case 2:
                if (($year % 4 === 0 && $year % 100 !== 0) || $year % 400 === 0) {
                    $day > 29 && ($day = 0);
                } else {
                    $day > 28 && ($day = 0);
                }
                break;
            default:
                $month = 0;
                $day = 0;
                break;
        }

        return sprintf('%04d-%02d-%02d', $year, $month, $day);
    }

    /**
     * 获取时间戳
     *
     * @param string $datetime YYYY-MM-DD hh:mm:ss 或 YYYY-MM-DD
     * @return int 
     */
    public static function getUnixTime($datetime) {
        if (!preg_match('/[^0-9]/', $datetime)) {
            return $datetime;
        }
        list($y, $m, $d, $h, $i, $s) = explode(' ', preg_replace('/\D+/i', ' ', $datetime));
        return mktime($h, $i, $s, $m, $d, $y);
    }

    /**
     * 获取简短的日期显示
     * 
     * @param string $date
     * @return string
     */
    public static function getShortDate($date) {
        return preg_replace('/0000-\d*-\d*|0000-00-\d*|-00-00|-00$/', '', preg_replace('/-|\/|\./', '-', $date));
    }

}
