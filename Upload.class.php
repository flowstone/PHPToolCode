<?php
	
	//文件上传类
	class Upload {
		//确定属性
		private $_max_size;
		private $_ext_list= array('.jpg','.png','.gif');
		private $_allow_mine_list= array('image/png','image/gif','image/jpeg','image/pjpeg','image/x-png');
		private $_upload_path;
		private $_prefix;
		public function __construct()
		{
			$this -> setMaxsize(4*1024*1024);
		}
		//给出对应的set方法
		public function setMaxsize($max_size){
			$this->_max_size=$max_size;
		}

		public function setExtList(array $ext_list = array('.jpg','.png','.gif')){
			$this->_ext_list=$ext_list;
		}

		public function setAllowMimeList(array $allow_mine_list=array('image/pgn','image/gif','image/jpeg','image/pjpeg','image/x-png')){
			$this->_allow_mine_list=$allow_mine_list;
		}

		public function setUploadPath ($upload_path){
			$this->_upload_path=$upload_path;
		}
		public function setPrefix($prefix){
			$this->_prefix=$prefix;
		}

		public function doUpload($tmp_file){
			//判断文件大小，当文件过大时给出提示，放弃上传
			if($tmp_file['size']>$this->_max_size){
				echo '你上传的文件过大';
				return false ;
			}
			if(!($tmp_file['error']===0)){
				echo '上传文件有误';
				return false ;
			}
			//增加一段代码，用来校验上传的文件类型是否正确
			//上传文件的后缀统一转成小写
			$ext = strtolower(strrchr($tmp_file['name'],'.'));

			if(!in_array($ext, $this->_ext_list)){
				echo '你上传的文件类型不对';
				return false;
			}
			//对文件进行第二级防护，对上传文件的MIME进行验证
			$mime_type =$tmp_file['type'];
			if(!in_array($mime_type, $this->_allow_mine_list)){
				echo '你上传的文件的mime不对';
				return false;
			}
			//对文件类型进行第三级防护，使用PHP程序对文件类型进行MIME检测
			//为了使用Finfo这个类，需要开启php.ini中一个扩展：extension php_fileinfo.dll
			$finfo = new Finfo(FILEINFO_MIME_TYPE);
			$mime_type=$finfo->file($tmp_file['tmp_name']);
			if (!in_array($mime_type, $this->_allow_mine_list)){
				echo '类型不合法';
				return false;
			}
			//这里我们增加一段代码，让文件名唯一
			$filename = uniqid($this->_prefix,true);
			//拼接一个完整唯一的文件名
			$upload_filename=$filename.$ext;

			//增加分目录存放处理，记得在最后带上/
			$sub_dir = date('Ymd').'/';
			//判断这个目录是否存在
			if (!is_dir($this->_upload_path.$sub_dir)){
				//如果目录不存在，则创建一个新的
				//die($this->_upload_path.$sub_dir);
				mkdir($this->_upload_path.$sub_dir,0777,true);
			}
			if (move_uploaded_file(iconv('gbk','utf-8',$tmp_file['tmp_name']), iconv('utf-8','gbk',$this->_upload_path.$sub_dir.$upload_filename))){
				return $sub_dir.$upload_filename;
			}else{
				return false;
			}
		} 
	}
