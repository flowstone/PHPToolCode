<?php
namespace framework\core;
	/**验证码
	*/
	class Imagecode{
		private $width ;
		private $height;
		private $counts;
		private $distrubcode;
		private $fonturl;
		private $session;//调试修改的
		// public $session;
		//$fonturl="C:\Windows\Fonts\STXINGKA.TTF"干扰点是这个字体
		public function __construct($width = 120,$height = 34,$counts = 4,$distrubcode="1235467890qwertyuipkjhgfdaszxcvbnmABCDEFJHIJKLMNQRSTUVWXYZ",$fonturl="C:\Windows\Fonts\calibrili.ttf"){
			$this->width=$width;
			$this->height=$height;
			$this->counts=$counts;
			$this->distrubcode=$distrubcode;
			$this->fonturl=$fonturl;
			$this->session=$this->sessioncode();
			//session_start();
			$_SESSION['code']=$this->session;
		}

		public function imageout(){
			$img=$this->createimagesource();
			$this->setbackgroundcolor($img);
			$this->set_code($img);
			$this->setdistrubecode($img);
			imageGIF($img);
			imageDestroy($img);
		}


		//创建画布并返回发布
		private function createimagesource(){
			return imagecreate($this->width,$this->height);
		}

		//设置图片的背景颜色
		private function setbackgroundcolor($img){
			//背景颜色偏向于白色
			$bgcolor = imageColorAllocate($img, rand(200,255),rand(200,255),rand(200,255));
			//区域填充???
			imagefill($img,0,0,$bgcolor);
		}
		//生成干扰字体
		private function setdistrubecode($img){
			$count_h=$this->height;
			$cou=floor($count_h*2);
			for($i=0;$i<$cou;$i++){
				$x=rand(0,$this->width);
				$y=rand(0,$this->height);
				$jiaodu=rand(0,360);
				$fontsize=rand(3,8);//此方法中的$fontsize代表干扰字母的大小
				$fonturl=$this->fonturl;
				$originalcode = $this->distrubcode;
				$countdistrub = strlen($originalcode);
				$dscode = $originalcode[rand(0,$countdistrub-1)];
				$color = imageColorAllocate($img, rand(40,140),rand(40,140),rand(40,140));
				imagettftext($img,$fontsize,$jiaodu,$x,$y,$color,$fonturl,$dscode);

			}
		}

		//生成验证码
		private function set_code($img){
				$width=$this->width;
				$counts=$this->counts;
				$height=$this->height;
				$scode=$this->session;
				$y=floor($height/2)+floor($height/4);
				$fontsize=rand(20,26);//生成验证码字体大小
				$fonturl="C:\Windows\Fonts\cambriab.ttf";//验证码的字体,字体可以从路径下寻找进行更换

				$counts=$this->counts;
				for($i=0;$i<$counts;$i++){
					$char=$scode[$i];
					$x=floor($width/$counts)*$i+8;
					$jiaodu=rand(-20,30);
					$color = imageColorAllocate($img,rand(0,50),rand(50,100),rand(100,140));
					imagettftext($img,$fontsize,$jiaodu,$x,$y,$color,$fonturl,$char);
				}

		}
		public function sessioncode(){//默认为private
				$originalcode = $this->distrubcode;
				$countdistrub = strlen($originalcode);
				$_dscode = "";
				$counts=$this->counts;
				for($j=0;$j<$counts;$j++){
					$dscode = $originalcode[rand(0,$countdistrub-1)];
					$_dscode.=$dscode;
				}
				// $this->session=$this->sessioncode();
				// session_start();
				// $_SESSION['code']=$this->session;
				return $_dscode;

		}
	}
