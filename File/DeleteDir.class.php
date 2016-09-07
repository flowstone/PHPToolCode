<?php

/**
 * 删除给定目录
 * 注意：此函数删除目录下的所有文件，包括目录本身。
 */
function delete_dir($dir) {
	//先删除目录下的文件：
	$dh = opendir($dir);
	while($file = readdir($dh)) {
		if($file != "." && $file != "..") {
			$fullpath = $dir . "/" . $file;
			if(!is_dir($fullpath)) {
			  unlink($fullpath);
			} else {
			  delete_dir($fullpath);
			}
		}
	}

	closedir($dh);
	
	//删除当前文件夹：
	if(rmdir($dir)) {
		return true;
	} else {
		return false;
	}
}
?>