<?php

class CheckCode{
    //验证码位数
    private $mCheckCodeNum=4;
    //验证码
    private $mCheckCode='';
    //验证码图片
    private $mCheckImage='';
    //验证码干扰素
    private $mDisturbColor='';
    //验证码图片宽度
    private $mCheckImageWidth='80';
    //验证码图片高度
    private $mCheckImageHeight='20';
      
    /**
     * 输出头
     */
    public function OutFileHeader(){
        header("content-type:image/png");
    }
      
   public function getCheckCode(){
		return $this->mCheckCode;
   }
    /**
     * 生成验证码
     */
    public function CreateCheckCode(){
        $this->mCheckCode=strtoupper(substr(md5(rand()),0,$this->mCheckCodeNum));
        return $this->mCheckCode;
    }
      
    /**
     * 生成验证码图片
     */
    public function CreateImage(){
        $this->mCheckImage=@imagecreate($this->mCheckImageWidth,$this->mCheckImageHeight);
        imagecolorallocate($this->mCheckImage,200,200,200);
        return $this->mCheckImage;
    }
      
    /**
     * 设置干扰素
     */
    public function SetDisturbColor(){
        for($i=0;$i<=128;$i++){
            $this->mDisturbColor=imagecolorallocate($this->mCheckImage,rand(0,255),rand(0,255),rand(0,255));
            imagesetpixel($this->mCheckImage,rand(2,128),rand(2,38), $this->mDisturbColor);
        }
    }
      
    /**
     * 设置验证码图片的大小
     * @param int $width
     * @param int $height
     */
    public function SetCheckImageWH(int $width,int $height){
        if($width==''||$height==''){
            return false;
        }
        $this->mCheckImageWidth=$width;
        $this->mCheckImageHeight=$height;
        return true;
    }
      
    /**
     * 将验证码逐个画到验证图片上
     */
    public function WriteCheckCodeToImage(){
        for($i=0;$i<$this->mCheckCodeNum;$i++){
            $bg_color=imagecolorallocate($this->mCheckImage,rand(0,255),rand(0,128),rand(0,255));
            //$i+0.3不让验证码从图片0位置开始画
            $x=floor($this->mCheckImageWidth/$this->mCheckCodeNum)*($i+0.3);
            $y=rand(0,$this->mCheckImageHeight-15);
            imagechar($this->mCheckImage,5,$x,$y,$this->mCheckCode[$i],$bg_color);
        }
    }
      
    /**
     * 输出验证码图片
     */
    public function OutCheckImage(){
        $this->OutFileHeader();
        $this->CreateCheckCode();
        $this->CreateImage();
        $this->SetDisturbColor();
        $this->WriteCheckCodeToImage();
        imagepng($this->mCheckImage);
        imagedestroy($this->mCheckImage);
    }
      
}

?>

