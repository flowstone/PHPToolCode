<?php
namespace framework\tools;
/**
 * 验证码工具类
 * CAPTCHA项目是Completely Automated Public Turing Test to Tell Computers and Humans Apart (全自动区分计算机和人类的图灵测试)的简称
 */
class Captcha {

	public $width = 100;
	public $height = 35;
	public $code_len = 4;

	public $pixel_number = 100;

	private function _mkCode() {
		// 有4位，大写字母和数字组成
		// 思路，将所有的可能性，做一个集合。随机（依据某种条件）进行选择。
		// 所有的可能的字符集合
		$chars = 'ABCDEFGHIJKLMNPQRSTUVWXYZ123456789';
		$chars_len = strlen($chars);// 集合长度
		// 随机选取
		$code = '';// 验证码值初始化
		for($i=0; $i<$this->code_len; ++$i) {
			// 随机取得一个字符下标
			$rand_index = mt_rand(0, $chars_len-1);
			// 利用字符串的下标操作，获得所选择的字符
			$code .= $chars[$rand_index];
		}
		// 存储于session中
		@session_start();
		$_SESSION['captcha_code'] = $code;
		return $code;

	}



	/**
	 * 生成验证码图片
	 * @param int $code_len 码值长度
	 */
	public function makeImage() {
		// 一：处理码值,这里我们添加了一个函数 _mkCode()生成随机的验证码
		$code = $this->_mkCode();
		
		// 二，处理图像
		// 创建画布
		$image = imagecreatetruecolor($this->width, $this->height);
		// 设置背景颜色[背景就会是随机]
		$bg_color = imagecolorallocate($image, mt_rand(200, 255), mt_rand(200, 255), mt_rand(200, 255));
		// 填充背景
		imagefill($image, 0, 0, $bg_color);

		// 分配字体颜色，随机分配，黑色或者白色
//		if (mt_rand(0, 1) == 1) {
			$str_color = imagecolorallocate($image, 0, 0, 0);// 黑色
//		} else {
//			// 整数支持 不同进制表示
//			$str_color = imagecolorallocate($image, 255, 0xff, 255);// 白色
//		}
		// 内置5号字体
		$font = 5;
		// 位置
		// 画布大小
		$image_w = imagesx($image);
		$image_h = imagesy($image);
		// 字体宽高
		$font_w = imagefontwidth($font);
		$font_h = imagefontheight($font);
		// 字符串宽高
		$str_w = $font_w * $code_len;
		$str_h = $font_h;
		// 计算位置[把我们字符串在中间]
		$str_x = ($image_w - $str_w) / 2 - 20;
		$str_y = ($image_h-$str_h) / 2;
		// 字符串
		imagestring($image, $font, $str_x, $str_y, $code, $str_color);

		// 设置干扰
		for($i=1; $i<=$this->pixel_number; ++$i) {
			$color = imagecolorallocate($image, mt_rand(0, 200), mt_rand(0, 200), mt_rand(0, 200));
			imagesetpixel($image, mt_rand(0, $this->width-1), mt_rand(0,$this->height-1), $color);
		}

		// 输出，销毁画布
		//ob_clean(),清空缓存，保证正确输出.
		ob_clean();
		header('Content-Type: image/jpeg');
		imagejpeg($image);
		imagedestroy($image);
	}

	}
