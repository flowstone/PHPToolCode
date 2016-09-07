<?php
class Image
{
	//定义一个数组保存文件类型和创建图像资源的函数
	private $create_func = array(
		'image/png'		=>		'imagecreatefrompng',
		'image/jpeg'	=>		'imagecreatefromjpeg',
		'image/gif'		=>		'imagecreatefromgif',
	);
	//文件类型 和 输出图像资源函数的映射关系
	private $output_func = array(
		'image/png'		=>	'imagepng',
		'image/jpeg'	=>	'imagejpeg',
		'image/gif'		=>	'imagegif'
	);
	//图像的类型
	private $mime;
	//压缩图像保存的目录
	private $thumb_path;
	//原图文件
	private $src_file;
	
	//构造方法中初始化图像资源
	public function __construct($src_file)
	{
		if(!file_exists($src_file)){
			die('文件不存在');
		}else{
			//初始化图像地址
			$this -> src_file = $src_file;	
			$this -> getMime($src_file);
		}
	}	
	//获得图像mime类型功能
	private function getMime($src_file)
	{
		//将来在创建图像资源时需要图像的类型，输出的时候也需要图像类型，所以我们将其封装到属性		
		$info = getimagesize($src_file);
		$this -> mime = $info['mime'];
	}
	//设置保存目录位置
	public function setThumbPath($thumb_path)
	{
		//先判断一下用户传递的目录是否存在
		if(!is_dir($thumb_path)){
			//没有设置目录的话，设置一个默认的
			$this -> thumb_path = './';
		}else{
			//如果设置了目录，则使用设置的
			$this->thumb_path = $thumb_path;
		}
	}
	//根据文件类型，获得创建图像资源的函数
	private function getCreateFunc()
	{
		return $this->create_func[$this -> mime];
	}
	//根据文件类型，获得输出图像的函数
	private function getOutputFunc()
	{
		return $this->output_func[$this->mime];
	}
	//开始压缩处理
	//参数就是压缩的目标图像宽度、高度
	public function makeThumb($area_w,$area_h)
	{
		//获得创建图像资源函数  imagecreatefrom
		$create_func = $this->getCreateFunc();
		//调用imagecreatefrom(图片地址)	
		$src_img = $create_func($this->src_file);
		
		//目标图像的x轴坐标
		$dst_x = 0;
		//目标图像的y轴坐标
		$dst_y = 0;
		
		//原图资源的起点x轴坐标
		$src_x = 0;
		//原图资源的起点y轴坐标
		$src_y = 0;
		
		//原图宽度
		$src_w = imagesx($src_img);	
		//原图的高度
		$src_h = imagesy($src_img);
		
		//在指定的范围内，根据等比例计算出来目标图像资源的宽度、高度
		if($src_w >= $src_h){
			//先固定目标图像的宽度
			$dst_w = $area_w;
			//缩放比例：$dst_w / $dst_h = $src_w / $src_h;
			//2/x = 3/6;
			$dst_h = (int)($dst_w * $src_h / $src_w);
		}else{
			$dst_h = $area_h;
			//缩放比例：$dst_w / $dst_h = $src_w / $src_h;
			$dst_w = (int)($src_w / $src_h * $dst_h);
		}
		
		//创建目标图像资源(内存中的画布)
		$dst_img = imagecreatetruecolor($dst_w,$dst_h);
		
		if($this->mime=='image/png'){
			//针对png格式的图像，背景透明化处理方法
			$color = imagecolorallocate($dst_img,255,255,255);
			imagecolortransparent($dst_img,$color);
			imagefill($dst_img,0,0,$color);			
		}		
		imagecopyresampled($dst_img,$src_img,$dst_x,$dst_y,$src_x,$src_y,$dst_w,$dst_h,$src_w,$src_h);
		
		//将图像资源保存到服务器，并返回压缩之后的图片地址，便于在其他地方使用
		//如果将图像资源输出到一个文件，需要通过第二个参数告诉文件名称
		$output_func = $this->getOutputFunc();			
		// ./app/public/static/thumb/20160901/thumb_bs.png
		$sub_path = date('Ymd').'/';
		if(!is_dir($this->thumb_path.$sub_path)){
			mkdir($this->thumb_path.$sub_path,0777,true);
		}
		$file_name = 'thumb_'.basename($this->src_file);
		
		$output_func($dst_img,$this->thumb_path.$sub_path.$file_name);//输出png格式的图像	
			
		//释放内存中图像资源
		imagedestroy($src_img);
		imagedestroy($dst_img);
		
		return $sub_path.$file_name;
	}	
}