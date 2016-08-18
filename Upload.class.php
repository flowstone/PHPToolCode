<?php

header('content-type:text/html;charset=utf-8');

class Upload{
	private $_upload_path = './upload/';
	private $_max_size = 2 * 1024 * 1024;
	private $_prefix;
	private $_allow_ext_list = array('.jpg','.gif','.png');
	private $_allow_mime_list = array('image/png', 'image/gif', 'image/jpeg', 'image/pjpeg', 'image/x-png');

	public function setUploadPath($upload_path){
		$this->_upload_path = $upload_path;
	}

	public function setMaxSize($maxSize){
		$this->_max_size = $maxSieze;
	}

	public function setPrefix($prefix){
		$this->_prefix = $prefix;
	}

	public function setAllowExtList($allow_ext_list){
		$this->_allow_ext_list = $allow_ext_list;
	}

	public function setAllowMimeList($allow_mime_list){
		$this->_allow_mime_list = $allow_mime_list;
	}

	public function showUpload($upload_file_info){

		if ($upload_file_info['error'] != 0) {
			echo '上传失败';
			return false;
		}
		if ($upload_file_info['size'] > $this->_max_size){
			return false;
		}
		
		//后缀
		$ext = strtolower(strrchr($upload_file_info['name'],'.'));
		//文件名不能相同
		$upload_filename = uniqid($this->_prefix,true).$ext;


		/**
		 * 分目录存放我们的文件
		 */
		$sub_dir = date('Ymd') . '/';

		if (!is_dir($this->_upload_path . $sub_dir)) {
			mkdir($this->_upload_path . $sub_dir);
		}
		// 验证上传的文件后缀是不是 要求图片类型(一级防护)
		

		if (!in_array($ext,$this->_allow_ext_list)) {
			return false;
		}

		//为了防止用户通过修改后缀名，来欺骗我们，需要使用一个文件 mime  类型(二级防护)
		
		if (!in_array($upload_file_info['type'],$this->_allow_mime_list)) {
			return false;
		}
		//这次加强对mime类型的验证，这里我们需要使用到一个新知识 FileInfo
		$finfo = new Finfo(FILEINFO_MIME_TYPE);
		$upload_file_mime = $finfo->file($upload_file_info['tmp_name']);
		if (!in_array($upload_file_mime,$this->_allow_mime_list)) {
			return false;
		}

		//移动文件
		if (move_uploaded_file($upload_file_info['tmp_name'],$this->_upload_path.$sub_dir.$upload_filename)) {
			return $this->_upload_path.$sub_dir.$upload_filename;
			
		} else {
			return false;
			
		}
	}

	
}


