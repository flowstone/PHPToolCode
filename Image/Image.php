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
 * Image
 * 图片处理工具类
 *
 * @author Dong Nan <hidongnan@gmail.com>
 * @date 2015-12-11
 */
class Image {

    /**
     * 生成缩略图
     * @param string $src_file
     * @param string $thumb_file
     * @param int $max_width
     * @param int $max_height
     * @param int $quality
     * @param string $watermark
     * @param float $watermark_scale
     * @param int $position
     * @param int $alpha
     * @return boolean
     */
    public static function thumb($src_file, $thumb_file = null, $max_width = 120, $max_height = 120, $quality = 90, $watermark = null, $watermark_scale = 0.3, $position = 9, $alpha = 60) {
        $status = false;
        if (file_exists($src_file) && ($max_width > 0 || $max_height > 0)) {
            list($ori_w, $ori_h, $type, $attr) = getimagesize($src_file);
            $ori_radio = $ori_w / $ori_h;
            if (($ori_radio > 1 && $max_width > 0) || ($ori_radio < 0 && $max_height == 0 && $max_width > 0)) {
                $thumb_w = $ori_w > $max_width ? $max_width : $ori_w;
                $thumb_h = $ori_h * $thumb_w / $ori_w;
            } else {
                $thumb_h = $ori_h > $max_height ? $max_height : $ori_h;
                $thumb_w = $ori_w * $thumb_h / $ori_h;
            }
            $img_r = self::getImage($src_file);
            if (!$img_r) {
                return $status;
            }
            $dst_r = ImageCreateTrueColor($thumb_w, $thumb_h);
            imagecopyresampled($dst_r, $img_r, 0, 0, 0, 0, intval($thumb_w), intval($thumb_h), intval($ori_w), intval($ori_h));
            //添加水印
            if ($watermark) {
                if (file_exists($watermark)) {
                    $watermark_info = getimagesize($watermark);
                    if ($watermark_info[0] < $thumb_w * $watermark_scale) {
                        $watermark_w = $watermark_info[0];
                        $watermark_h = $watermark_info[1];
                    } else {
                        $watermark_w = $thumb_w * $watermark_scale;
                        $watermark_h = $watermark_info[1] * $watermark_w / $watermark_info[0];
                    }
                    //留边
                    $space_x = 0.1 * $watermark_w;
                    $space_y = 0.1 * $watermark_h;
                    switch ($position) {
                        //左上
                        case 1:
                            $dst_x = $space_x;
                            $dst_y = $space_y;
                            break;
                        //中上
                        case 2:
                            $dst_x = (int) ($thumb_w - $watermark_w) / 2;
                            $dst_y = $space_y;
                            break;
                        //右上
                        case 3:
                            $dst_x = $thumb_w - $space_x - $watermark_w;
                            $dst_y = $space_y;
                            break;
                        //左中
                        case 4:
                            $dst_x = $space_x;
                            $dst_y = (int) ($thumb_h - $watermark_h) / 2;
                            break;
                        //正中
                        case 5:
                            $dst_x = (int) ($thumb_w - $watermark_w) / 2;
                            $dst_y = (int) ($thumb_h - $watermark_h) / 2;
                            break;
                        //右中
                        case 6:
                            $dst_x = $thumb_w - $space_x - $watermark_w;
                            $dst_y = (int) ($thumb_h - $watermark_h) / 2;
                            break;
                        //左下
                        case 7:
                            $dst_x = $space_x;
                            $dst_y = $thumb_h - $space_y - $watermark_h;
                            break;
                        //左中
                        case 8:
                            $dst_x = (int) ($thumb_w - $watermark_w) / 2;
                            $dst_y = $thumb_h - $space_y - $watermark_h;
                            break;
                        //右下
                        case 9:
                        default:
                            $dst_x = $thumb_w - $space_x - $watermark_w;
                            $dst_y = $thumb_h - $space_y - $watermark_h;
                            break;
                    }
                    $watermark_handle = self::getImage($watermark);
                    $watermark_thumb = ImageCreateTrueColor($watermark_w, $watermark_h);
                    if (($watermark_info[2] == IMAGETYPE_GIF) || ($watermark_info[2] == IMAGETYPE_PNG)) {
                        $trnprt_indx = imagecolortransparent($watermark_handle);
                        if ($trnprt_indx >= 0) {
                            $trnprt_color = imagecolorsforindex($watermark_handle, $trnprt_indx);
                            $trnprt_indx = imagecolorallocate($watermark_thumb, $trnprt_color['red'], $trnprt_color['green'], $trnprt_color['blue']);
                            imagefill($watermark_thumb, 0, 0, $trnprt_indx);
                            imagecolortransparent($watermark_thumb, $trnprt_indx);
                        } elseif ($watermark_info[2] == IMAGETYPE_PNG) {
                            imagealphablending($watermark_thumb, true);
                            $color = imagecolorallocatealpha($watermark_thumb, 0, 0, 0, 0);
                            imagefill($watermark_thumb, 0, 0, $color);
                            imagecolortransparent($watermark_thumb, $color);
                            imagesavealpha($watermark_thumb, true);
                        }
                    }
                    imagecopyresized($watermark_thumb, $watermark_handle, 0, 0, 0, 0, $watermark_w, $watermark_h, $watermark_info[0], $watermark_info[1]);
                    imagecopymerge($dst_r, $watermark_thumb, $dst_x, $dst_y, 0, 0, $watermark_w, $watermark_h, $alpha);
                    imagedestroy($watermark_handle);
                    imagedestroy($watermark_thumb);
                }
            }
            if ($thumb_file) {
                $thumb_path = dirname($thumb_file);
                if (!is_dir($thumb_path)) {
                    mkdir($thumb_path, 0777, true);
                }
                $status = imagejpeg($dst_r, $thumb_file, $quality);
            } else {
                header("content-type: image/jpeg");
                $status = imagejpeg($dst_r, null, $quality);
            }
            imagedestroy($dst_r);
            imagedestroy($img_r);
        }
        return $status;
    }

    /**
     * 保存截取的图片
     * @param string $src_file
     * @param int $src_w
     * @param int $src_h
     * @param int $src_x
     * @param int $src_y
     * @param int $quality
     * @param string $dst_file
     * @param int $dst_w
     * @param int $dst_h
     * @return boolean
     */
    public static function crop($src_file, $src_w, $src_h, $src_x, $src_y, $quality = 90, $dst_file = '', $dst_w = 0, $dst_h = 0) {
        $status = false;
        if (file_exists($src_file)) {
            $radio = $src_w / $src_h;
            if ($dst_w == 0 && $dst_h == 0) {
                $dst_w = $src_w;
                $dst_h = $src_h;
            } elseif (($radio > 1 && $dst_w > 0) || ($radio < 0 && $dst_h == 0 && $dst_w > 0)) {
                $dst_h = $src_h * $dst_w / $src_w;
            } else {
                $dst_w = $src_w * $dst_h / $src_h;
            }
            $img_r = self::getImage($src_file);
            if (!$img_r) {
                return $status;
            } else {
                $dst_r = ImageCreateTrueColor($dst_w, $dst_h);
                imagecopyresampled($dst_r, $img_r, 0, 0, intval($src_x), intval($src_y), intval($dst_w), intval($dst_h), intval($src_w), intval($src_h));
                if ($dst_file) {
                    $dst_path = dirname($dst_file);
                    if (!is_dir($dst_path)) {
                        mkdir($dst_path, 0777, true);
                    }
                    $status = imagejpeg($dst_r, $dst_file, $quality);
                } else {
                    header("content-type: image/jpeg");
                    $status = imagejpeg($dst_r, null, $quality);
                }
                imagedestroy($dst_r);
                imagedestroy($img_r);
            }
        }
        return $status;
    }

    /**
     * 获取图片资源
     * @param string $filename
     * @return resource
     */
    private static function getImage($filename) {
        $image = null;
        if (file_exists($filename)) {
            $imginfo = getimagesize($filename);
            switch ($imginfo[2]) {
                case 1:
                case 'image/gif':
                    $image = imagecreatefromgif($filename);
                    break;
                case 2:
                case 'image/pjpeg':
                case 'image/jpeg':
                    $image = imagecreatefromjpeg($filename);
                    break;
                case 3:
                case 'image/x-png':
                case 'image/png':
                    $image = imagecreatefrompng($filename);
                    break;
                case 6:
                case 'image/bmp':
                case 'image/vnd.wap.wbmp':
                    $image = imagecreatefromwbmp($filename);
                    break;
            }
        }
        return $image;
    }

}
