<?php
namespace framework\tools;
class Verify
{
	//定义属性保存错误信息
	private $error = array();
	//显示错误信息
	public function getError()
	{
		$error_message = '';
		foreach($this->error as $row){
			$error_message .= $row.'<br>';
		}
		return $error_message;
	}
	//验证用户名是否符合规则
	//参数1：需要验证的数据
	//参数2：用户名最少多少位
	//参数3：用户名最多多少位
	public function checkUser($data,$min=5,$max=29)
	{
		//定义用户名的规则
		$reg = '/^[a-zA-Z]\w{'.$min.','.$max.'}/'; //6-30位
		preg_match($reg,$data,$match);
		if($match){
			return true;
		}else{
			$this -> error[] = "用户名应该是字母开头，后面字母数字、下划线的组合，至少{$min}个字符,最多{$max}字符";
 			return false;
		}
	}
	//验证密码是否符合规则
	public function checkPass($data,$min=6,$max=20)
	{
		$reg = '/[a-zA-Z\d~!@#$%^&\*()\-_\+\?\|\.\{\}\[\]:;\'"<>\/]{'.$min.','.$max.'}/';
		preg_match($reg,$data,$match);
		if($match){
			return true;
		}else{
			$this -> error[] = "{$min}-{$max}位字母、数字、或特殊符号的组合";
			return false;
 		}
	}	
	//验证邮箱是否符合规则
	public function checkEmail($data)
	{
		$reg = '/\w{1,20}@[a-zA-Z\d\.]{1,10}\.[a-zA-Z]{2,3}/';
		$reg = '/^[\w\-\.]+@[a-zA-Z\d]+(\.[a-zA-Z\d]+)*\.[a-zA-Z]{2,3}/';
		//also@vip.qq.vip.com
		preg_match($reg,$data,$match);
		if($match){
			return true;
		}else{
			$this -> error[] = "邮箱格式不正确";
			return false;
 		}
	}
}