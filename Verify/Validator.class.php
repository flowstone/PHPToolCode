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
 * Validator
 * 通用验证类
 *
 * @author Dong Nan <hidongnan@gmail.com>
 * @date 2015-8-23
 */
class Validator {

    /**
     * 校验
     * @param array $checks
     * @param array $data
     * @param int $when LT_MODEL_INSERT|LT_MODEL_UPDATE
     * @return array
     */
    public static function check($checks, $data, $when) {
        $status = false;
        $error = [];

        foreach ($checks as $key => $check) {
            $which = LT_VALIDATE_EXISTS;
            //没有设置 when,则每次都验证;设置了指定的验证时间,则只在满足指定验证时间时验证
            if (!empty($check['when'])) {
                if (isset($check['when'][$when])) {
                    $which = $check['when'][$when];
                } elseif (isset($check['when'][LT_MODEL_BOTH])) {
                    $which = $check['when'][LT_MODEL_BOTH];
                } else {
                    continue;
                }
            }
            //存在字段则验证
            if ($which == LT_VALIDATE_EXISTS && !isset($data[$key])) {
                continue;
            }
            //值不为空则验证
            if ($which == LT_VALIDATE_VALUE && empty($data[$key])) {
                continue;
            }
            //如果需要去除标签
            if ($check['type'] == 'strip_tags') {
                $data[$key] = strip_tags($data[$key]);
            }
            //其他情况正常验证
            if (empty($data[$key])) {
                //必填
                if (isset($check['required']) && $check['required']) {
                    $error[$key] = '请' . ($check['type'] === 'select' ? '选择' : '输入') . $check['name'];
                    continue;
                }
            } else {
                //长度不足
                if (isset($check['minlen']) && String::strlenUtf8($data[$key]) < $check['minlen']) {
                    $error[$key] = "{$check['name']}长度不能小于{$check['minlen']}个字符";
                    continue;
                }
                //长度超出
                if (isset($check['maxlen']) && String::strlenUtf8($data[$key]) > $check['maxlen']) {
                    $error[$key] = "{$check['name']}长度不能大于{$check['maxlen']}个字符";
                    continue;
                }
                //小于值
                if (isset($check['min']) && $data[$key] < $check['min']) {
                    $error[$key] = "{$check['name']}不能小于{$check['min']}";
                    continue;
                }
                //大于值
                if (isset($check['max']) && $data[$key] > $check['max']) {
                    $error[$key] = "{$check['name']}不能大于{$check['max']}";
                    continue;
                }
                //验证日期
                if (isset($check['looseDate']) && $check['looseDate'] && !self::checkLooseDate($data[$key])) {
                    $error[$key] = "{$check['name']}格式不正确";
                    continue;
                }
                //验证 in
                if (isset($check['in']) && !in_array($data[$key], $check['in'])) {
                    $error[$key] = "{$check['name']}的值不正确";
                    continue;
                }
                //验证邮箱
                if (isset($check['email']) && $check['email'] && !self::checkEmail($data[$key])) {
                    $error[$key] = "{$check['name']}格式不正确";
                    continue;
                }
                //验证电话号码
                if (isset($check['tel']) && $check['tel'] && !self::checkTel($data[$key])) {
                    $error[$key] = "{$check['name']}格式不正确";
                    continue;
                }
                //验证手机号
                if (isset($check['mobile']) && $check['mobile'] && !self::checkMobile($data[$key])) {
                    $error[$key] = "{$check['name']}格式不正确";
                    continue;
                }
                //验证邮政编码
                if (isset($check['zipcode']) && $check['zipcode'] && !self::checkZipCode($data[$key])) {
                    $error[$key] = "{$check['name']}格式不正确";
                    continue;
                }
                //验证身份证号
                if (isset($check['idcardnum']) && $check['idcardnum'] && !self::checkIdNum($data[$key])) {
                    $error[$key] = "{$check['name']}格式不正确";
                    continue;
                }
            }
        }

        if (empty($error)) {
            $status = true;
        }
        return ['status' => $status, 'error' => $error];
    }

    /**
     * 检查邮件地址
     * @param  string    $email         邮件地址字符串
     * @param  bool      $required      是必要项
     * @return bool                     通过验证
     */
    public static function checkEmail($email, $required = FALSE) {
        $status = FALSE;
        $email = trim($email);
        if ($email != '' && strstr($email, '@') && strstr($email, '.')) {
            $pattern = '/([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)/';
            $status = preg_match($pattern, $email) ? TRUE : FALSE;
        } elseif ($email == '' && $required === FALSE) {
            $status = TRUE;
        }
        return $status;
    }

    /**
     * 检查字符串
     * @param  string    $str           待查字符串
     * @param  int       $min           最小长度
     * @param  int       $max           最大长度($max=0则不作长度检查)
     * @param  bool      $required      是必要项
     * @return bool                     通过验证
     */
    public static function checkStr($str, $min = 0, $max = 0, $required = FALSE, $isUtf8 = FALSE) {
        $status = FALSE;
        if ($str == '' && $required === FALSE) {
            $status = TRUE;
        }
        if ($str != '' && is_string($str)) {
            if ($max > 0) {
                $len = $isUtf8 ? String::strlenUtf8($str) : strlen($str);
                $status = ($len >= $min && $len <= $max) ? TRUE : FALSE;
            } else {
                $status = TRUE;
            }
        }
        return $status;
    }

    /**
     * 检查整数
     * @param  int       $num           待查整数
     * @param  int       $min           最小值
     * @param  int       $max           最大值(如果没有值范围限制，此处传0过来)
     * @param  bool      $required      是必要项
     * @return bool                     通过验证
     */
    public static function checkInt($num, $min = 0, $max = 0, $required = FALSE) {
        $status = FALSE;

        if (!$num && $required === FALSE) {
            $status = TRUE;
        }
        // 考虑到表单传过来整数需要转换等情况，故用此种方式
        if (is_numeric($num)) {
            $num += 0;
            if (is_int($num)) {
                if ($max > 0) {
                    $status = ($num >= $min && $num <= $max) ? TRUE : FALSE;
                } else {
                    $status = TRUE;
                }
            }
        }
        return $status;
    }

    /**
     * 检查使用HTTP协议的网址
     * @param  string    $str           待查url
     * @param  bool      $required      是必要项
     * @return bool                     通过验证
     */
    public static function checkHttpUrl($str, $required = FALSE) {
        $status = FALSE;
        $str = trim($str);
        if ($str == '' && $required === FALSE) {
            $status = TRUE;
        }
        if ($str != '') {
            $pattern = '/^http:\/\/[A-Za-z0-9]+\.[A-Za-z0-9]+[\/=\?%\-&_~`@[\]\':+!]*([^<>\"\"])*$/';
            $status = preg_match($pattern, $str) ? TRUE : FALSE;
        }
        return $status;
    }

    /**
     * 检查电话号码 ,修改后需要将验证放开
     * @param  string    $str           待查电话号码(正确格式010-87786632或(010)32678878或32678878)
     * @param  bool      $required      是必要项
     * @return bool                     通过验证
     */
    public static function checkTel($str, $required = FALSE) {
        $status = FALSE;
        $str = trim($str);
        if ($str == '' && $required === FALSE) {
            $status = TRUE;
        }
        if ($str != '') {
            $pattern = '/^[+]{0,1}[\(]?(\d){1,3}[\)]?[ ]?([-]?((\d)|[ ]){1,12})+$/';
            $status = preg_match($pattern, $str) ? TRUE : FALSE;
        }
        return $status;
    }

    /**
     * 检查手机号码
     * @param  string    $str           待查手机号码
     * @param  bool      $required      是必要项
     * @return bool                     通过验证
     */
    public static function checkMobile($str, $required = FALSE) {
        $status = FALSE;
        $str = trim($str);
        if ($str == '' && $required === FALSE) {
            $status = TRUE;
        }
        if ($str != '') {
            $pattern = '/^((\(\d{3}\))|(\d{3}\-))?1[3,4,5,7,8]\d{9}$/';
            $status = preg_match($pattern, $str) ? TRUE : FALSE;
            if ($status === FALSE) {
                $pattern = '/^09\d{8}$/';
                $status = preg_match($pattern, $str) ? TRUE : FALSE;
            }
            if ($status === FALSE) {
                $pattern = '/^00852\d{8}$/';
                $status = preg_match($pattern, $str) ? TRUE : FALSE;
            }
        }
        return $status;
    }

    /**
     * 检查邮编
     * @param  string    $str           待查邮编
     * @param  bool      $required      是必要项
     * @return bool                     通过验证
     */
    public static function checkZipCode($str, $required = FALSE) {
        $status = FALSE;
        $str = trim($str);
        if ($str == '' && $required === FALSE) {
            $status = TRUE;
        }
        if ($str != '') {
            $pattern = '/^[0-9]\d{5}$/';
            $status = preg_match($pattern, $str) ? TRUE : FALSE;
        }
        return $status;
    }

    /**
     * 检查身份证号码
     * @param  string    $str           待查身份证号码
     * @param  bool      $required      是必要项
     * @return bool                     通过验证
     */
    public static function checkIdNum($str, $required = FALSE) {
        $status = FALSE;
        $str = trim($str);
        if ($str == '' && $required === FALSE) {
            $status = TRUE;
        }
        if ($str != '') {
            $pattern = '/(^([\d]{15}|[\d]{18}|[\d]{17}[xX]{1})$)/';
            $status = preg_match($pattern, $str) ? TRUE : FALSE;
        }
        return $status;
    }

    /**
     * 检查日期是否正确
     * @param  string       $str        日期(格式：2007-04-25)
     * @return bool                     通过验证
     */
    public static function checkDate($str) {
        $status = FALSE;
        $str = trim($str);
        if (preg_match('/^[0-9]{4}-[0-9]{1,2}-[0-9]{1,2}$/', $str)) {
            $dateArr = explode('-', $str);
            $status = (checkdate($dateArr[1], $dateArr[2], $dateArr[0])) ? TRUE : FALSE;
        }
        return $status;
    }

    /**
     * 检查开始日期是否小于结束日期
     * @param  str       $begin         开始日期(格式：2007-04-25)
     * @param  str       $end           结束日期(格式：2007-04-28)
     * @return bool                     通过验证
     */
    public static function checkDateRange($begin, $end) {
        $status = FALSE;
        if (self::checkDate($begin) && self::checkDate($end)) {
            $status = ((strtotime($end) - strtotime($begin)) > 0) ? TRUE : FALSE;
        }
        return $status;
    }

    /**
     * 检查开始时间是否小于结束时间
     * @param  str       $begin         开始日期(格式：2007-04-25 10:00:00)
     * @param  str       $end           结束日期(格式：2007-04-28 10:00:00)
     * @return bool                     通过验证
     */
    public static function checkDatetimeRange($begin, $end) {
        $status = FALSE;
        if (self::isDatetime($begin) && self::isDatetime($end)) {
            $status = ((strtotime($end) - strtotime($begin)) > 0) ? TRUE : FALSE;
        }
        return $status;
    }

    /**
     * 检查是否为合法金额
     * @param  string       $str        金额字符串
     * @param  string       $required   是否必填
     * @param  integer      $length     整数部分的最大位数
     * @return bool                     通过验证
     */
    public static function checkMoney($str, $required = FALSE, $length = 8) {
        $status = FALSE;
        $str = trim($str);
        if ($str == '' && $required === FALSE) {
            $status = TRUE;
        }
        if ($str != '') {
            $pattern = '/^[0-9]{1,' . $length . '}[.]{0,1}[0-9]{0,2}$/';
            $status = preg_match($pattern, $str) ? TRUE : FALSE;
        }
        return $status;
    }

    /**
     * 检查是否为一个合法的日期格式
     * @param   string $date
     * @return boolean
     */
    public static function isDate($date) {
        $pattern = '/[\d]{4}-[\d]{1,2}-[\d]{1,2}/';
        return (boolean) preg_match($pattern, $date);
    }

    /**
     * 检查是否为一个合法的时间格式
     * @param   string $time
     * @return boolean
     */
    public static function isDatetime($time) {
        $pattern = '/[\d]{4}-[\d]{1,2}-[\d]{1,2}\s[\d]{1,2}:[\d]{1,2}:[\d]{1,2}/';
        return (boolean) preg_match($pattern, $time);
    }

    /**
     * 检测一个宽松的日期格式
     * @param   string  $date   要检查的日期字符串，如：2008,2008-08,2008-08-08或2008.8.8等等
     * @return boolean
     */
    public static function checkLooseDate($date) {
        $pattern = '/[\d]{4}|[\d]{4}[-\/\.][\d]{1,2}|[\d]{4}[-\/\.][\d]{1,2}[-\/\.][\d]{1,2}/';
        return (boolean) preg_match($pattern, $date);
    }

    /**
     * 判断是否是浮点数
     * @param String $value 要检查的值
     * @param int $num 小数点位数
     * @param int $max 整数位最大长度
     * @return boolean
     */
    public static function checkFloat($value, $num, $max = 5) {
        $pattern = '/^[0-9]{1,' . $max . '}[\.]{0,1}[0-9]{0,' . $num . '}$/';
        return (boolean) preg_match($pattern, $value);
    }

}
