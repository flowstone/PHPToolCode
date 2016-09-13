<?php

/*******************************字符处理*******************************/

function strnl2br($val){
	return preg_replace('/\s/i', '&nbsp;', $val);
}

define('APPSTR', '');

function AppStrSub($str, $end = 80, $start = 0){
	global $config;
	//echo $end;
	$SubStr=csubstr($str, $start, $end, $config->charset, $suffix=true);
	return $SubStr;
}

function StrSub($str, $end = 80, $start = 0){
	global $config;
	//echo $end;
	$SubStr=csubstr($str, $start, $end, $config->charset, $suffix=false);
	return $SubStr;
}
/*
* 中文截取，支持gb2312,gbk,utf-8,big5
*
* @param string $str 要截取的字串
* @param int $start 截取起始位置
* @param int $length 截取长度
* @param string $charset utf-8|gb2312|gbk|big5 编码
* @param $suffix 是否加尾缀
*/
function csubstr($str, $start=0, $length, $charset="utf-8", $suffix=true){
	if($start){
		$str=substr($str,$start);
	}
	if ($charset=='utf-8'){
		$charlong=3;
	}else{
		$charlong=2;
	}
	$length=$length*2;
	$allstr=$str;
	for($i=0;$i<$length;$i++){
		$temp_str=substr($str,0,1);
		if(ord($temp_str) > 127){
				$i++;
				if($i<$length){
					$new_str[]=substr($str,0,$charlong);
					$str=substr($str,$charlong);
				}
		}else{
			$new_str[]=substr($str,0,1);
			$str=substr($str,1);
		}
	}
/*	if(function_exists("mb_substr")){
		if(mb_strlen($str, $charset) <= $length) return $str;
		$slice = mb_substr($str, $start, $length, $charset);
	}else{
		$re['utf-8']   = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
		$re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
		$re['gbk']          = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
		$re['big5']          = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
		preg_match_all($re[$charset], $str, $match);
		if(count($match[0]) <= $length) return $str;
		$slice = join("",array_slice($match[0], $start, $length));
	}*/
	$slice =join($new_str);
	if ($slice==$allstr){return $allstr;}
	if($suffix) return $slice.APPSTR;
	return $slice;
}
/**
* 将字符串转换为数组
*
* @param	string	$data	字符串
* @return	array	返回数组格式，如果，data为空，则返回空数组
*/
function string2array($data) {
	if($data == '') return array();
	@eval("\$array = $data;");
	return $array;
}
/**
* 将数组转换为字符串
*
* @param	array	$data		数组
* @param	bool	$isformdata	如果为0，则不使用new_stripslashes处理，可选参数，默认为1
* @return	string	返回字符串，如果，data为空，则返回空
*/
function array2string($data, $isformdata = 1) {
	if($data == '') return '';
	if($isformdata) $data = new_stripslashes($data);
	return addslashes(var_export($data, TRUE));
}
/**
 * 返回经stripslashes处理过的字符串或数组
 * @param $string 需要处理的字符串或数组
 * @return mixed
 */
function new_stripslashes($string) {
	if(!is_array($string)) return stripslashes($string);
	foreach($string as $key => $val) $string[$key] = new_stripslashes($val);
	return $string;
}
function headerPage($url = '', $phpFunc = true){
	if ($url == '')
	$url = parseURL();
	if ($phpFunc){
		header('location:'.$url);
	} else {
		echo '<meta http-equiv="refresh" content="0;URL='.$url.'">';
	}
}

//获取IP地址
  function GetIP(){
		if (getenv("HTTP_CLIENT_IP") && strcasecmp(getenv("HTTP_CLIENT_IP"), "unknown"))
		     $ip = getenv("HTTP_CLIENT_IP");
		else if (getenv("HTTP_X_FORWARDED_FOR") && strcasecmp(getenv("HTTP_X_FORWARDED_FOR"), "unknown"))
		     $ip = getenv("HTTP_X_FORWARDED_FOR");
		else if (getenv("REMOTE_ADDR") && strcasecmp(getenv("REMOTE_ADDR"), "unknown"))
		     $ip = getenv("REMOTE_ADDR");
		else if (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], "unknown"))
		     $ip = $_SERVER['REMOTE_ADDR'];
		else
		    $ip = "unknown";
		return($ip);
	}

// Return visitor's OS
function getOS(){
	$agent = $_SERVER["HTTP_USER_AGENT"];

	if (preg_match('/Win/i', $agent) && preg_match('/NT 5.2/i', $agent)){
		// Windows 2003
		$os = "Win2003";
	}
	elseif (preg_match('/Win/i', $agent) && preg_match('/NT 5.1/i', $agent)){
		// Windows XP
		$os = "WinXP";
	}
	elseif (preg_match('/Win/i', $agent) && preg_match('/NT 5.0/i', $agent)){
		// Windows 2000
		$os = "Win2000";
	}
	elseif (preg_match('/Win/i', $agent) && preg_match('/NT/i', $agent)){
		// Windows NT
		$os = "Win2000";
	}
	elseif (preg_match('/Win/i', $agent) && preg_match('/4.90/i', $agent)){
		// Windows ME
		$os = "Win9X";
	}
	elseif (preg_match('/Win/i', $agent) && preg_match('/98/i', $agent)){
		// Windows 98
		$os = "Win9X";
	}
	elseif (preg_match('/Win/i', $agent) && preg_match('/95/i', $agent)){
		// Windows 95
		$os = "Win9X";
	}
	elseif (preg_match('/Win/i', $agent) && preg_match('/32/i', $agent)){
		// Windows 32
		$os = "Win9X";
	}
	elseif (preg_match('/android/i', $agent)){
		// linux
		$os = "Android";
	}
	elseif (preg_match('/iphone/i', $agent)){
		// linux
		$os = "iPhone";
	}	
	elseif (preg_match('/Linux/i', $agent)){
		// linux
		$os = "Linux";
	}
	elseif (preg_match('/BSD/i', $agent)){
		// *BSD
		$os = "Unix";
	}
	elseif (preg_match('/Unix/i', $agent)){
		// Unix
		$os = "Unix";
	}
	elseif (preg_match('/Sun/i', $agent)){
		// SunOS
		$os = "SunOS";
	}
	elseif (preg_match('/Mac/i', $agent)){
		// Macintosh
		$os = "Macintosh";
	}
	elseif (preg_match('/IBM/i', $agent)){
		// IBMOS
		$os = "IBMOS";
	} else { // Other
		$os = "Other";
	}

	return trim($os);
}
// Return visitor's browser
function getBrowser(){
	$agent = $_SERVER["HTTP_USER_AGENT"];

	if (preg_match('/Firefox/i', $agent)){
		$browser = "Firefox";
	}
	elseif (preg_match('/Netscape/i', $agent)){
		$browser = "Netscape";
	}
	elseif (preg_match('/NetCaptor/i', $agent)){
		$browser = "NetCaptor";
	}
	elseif (preg_match('/MSN/i', $agent)){
		$browser = "MSN Explorer";
	}
	elseif (preg_match('/Opera/i', $agent)){
		$browser = "Opera";
	}
	elseif (preg_match('/AOL/i', $agent)){
		$browser = "AOL";
	}
	elseif (preg_match('/JAVA/i', $agent)){
		$browser = "JAVA";
	}
	elseif (preg_match('/MacWeb/i', $agent)){
		$browser = "MacWeb";
	}
	elseif (preg_match("/MSIE/i", $agent)){
		$str = explode(";", $agent);
		$str = $str['1'];
		$str = explode(" ", $str);
		$browser_ver = $str['2'];
		if ($browser_ver == "6.0"){
			$browser = "IE6";
		} else {
			$browser = "IE5";
		}
	} else {
		$browser = "OtherBrowser";
	}
	return trim($browser);
}
function checkReferer(){//验证访问来源
	global $config;
	$url=getHttpReferer();
	if(!preg_match("/{$url}/i",$_SERVER['HTTP_HOST'])){
		errorNote ( _ERROR_REFERER_URL );
	}
}
function getHttpReferer($whole=true){//获取来源URL  $whole：是否只获取当前域名
	$referer=$_SERVER['HTTP_REFERER'];
	if($whole){
		$refer=parse_url($referer);
		return $refer['host'];
	}
	return $referer;
}

// 去掉空白字符
function RemoveSpace(& $document){
	$search = array ("'([\r\n])[\s]+'");

	$replace = array ("");
	$document = preg_replace($search, $replace, $document);

}

function _htmlspecialchars(& $array){
	if (is_array($array)){
		foreach ($array as $_key => $_value){
			$array[$_key] = htmlspecialchars($_value);
		}
	}
	return $array;
}

function ConnStr($array, $chrs = ','){
	//$strs = '';//这是原来的代码，不利于搜索

	$strs = $chrs;//20091224张兴忠修改此处代码

	if(is_array($array)){
		foreach ($array as $key => $vl){
			$strs .= $vl.$chrs;
		}
	}
	return $strs;
}

function repstr($strs, $shows){

	for ($i = 0; $i < count($shows); $i ++){
		$name = "{".$shows[$i]['name']."}";
		$cons = $shows[$i]['cons'];
		$strs = preg_replace("/{$name}/", $cons, $strs);
	}
	return $strs;
}

function getStrsParamArray($strs){  //分解字符参数
	$_newarray=array();
	$tmp=explode(',',$strs);
	foreach($tmp as $key=>$vl){
		$_tmp=explode(':',$vl);
		$_newarray[$_tmp[0]]=$_tmp[1];
	}
	//	print_r($_newarray);
	return $_newarray;
}

function getArrayParamStrs($vars){  //分解字符参数
	$strs='';
	if(is_array($vars)){
		foreach($vars as $key=>$vl){
			if(empty($strs)) $strs.=$key.":".$vl;
			else $strs.=",".$key.":".$vl;
		}
	}
	return $strs;
}


function getArrayParamStr($vars){  //分解字符参数
	$strs='';
	if(is_array($vars)){
		foreach($vars as $key=>$vl){
			 $strs.=$vl.",";
		}
	}
	return $strs;
}
/*function SearchSql($tmpqu){

if (is_array($tmpqu)){
$i = 0;
foreach ($tmpqu as $key => $vl){
if (!empty ($vl)){
if ($i != 0){
$query .= " and ".$tmpqu[$key];
} else {
$query .= " where ".$tmpqu[$key];
}
$i ++;
}
}
}
return $query;
}*/
//20091208张兴忠修改
function SearchSql($tmpqu,$split='and') {

	if (is_array($tmpqu)) {

		$i = 0;
		foreach ($tmpqu as $key => $vl) {

			if (!empty ($vl)) {

				if ($i != 0) {
					$query .= " {$split} ".$tmpqu[$key];
				} else {

					$query .= " where ".$tmpqu[$key];
				}
				$i ++;
			}
		}
	}
	return $query;
}

function split_sql($sql){
	$sql = trim($sql);
	$sql = preg_replace("/\-\- [^\n]*\n/", "\n", $sql);

	$buffer = array();
	$ret = array();
	$in_string = false;

	for($i=0; $i< strlen($sql)-1; $i++){
		if($sql[$i] == ";" && !$in_string){
			$ret[] = substr($sql, 0, $i);
			$sql = substr($sql, $i + 1);
			$i = 0;
		}

		if($in_string && ($sql[$i] == $in_string) && $buffer[1] != "\\"){
			$in_string = false;
		}
		elseif(!$in_string && ($sql[$i] == '"' || $sql[$i] == "'") && (!isset($buffer[0]) || $buffer[0] != "\\")){
			$in_string = $sql[$i];
		}
		if(isset($buffer[1])){
			$buffer[0] = $buffer[1];
		}
		$buffer[1] = $sql[$i];
	}

	if(!empty($sql)){
		$ret[] = $sql;
	}
	return($ret);
}

/*******************************时间处理*******************************/

function getMicrotime(){
	list ($usec, $sec) = explode(' ', microtime());
	return (float) $sec + ((float) $usec);
}

function makeSeed(){
	list ($usec, $sec) = explode(' ', microtime());
	return (float) $sec + ((float) $usec * 100000);
}

function rnd_id(){
	list ($usec, $sec) = explode(' ', microtime());
	return (float) $sec + ((float) $usec * 1000000);
}

//出生日期获取年龄
function calculateActualAge($birthday){
	$birthdayArray = explode("-", $birthday);

	$year = intval($birthdayArray[0]);
	$month = intval($birthdayArray[1]);
	$day = intval($birthdayArray[2]);

	$nowYear = intval(date('Y'));
	$nowMonth = intval(date('m'));
	$nowDay = intval(date('d'));

	$age = $nowYear - $year;

	if ($nowMonth > $month){
		$age ++;
	}
	elseif ($nowMonth = $month){
		if ($nowDay >= $day){
			$age ++;
		}
	}
	return $age;
}

function Tdate($strs, $num, $sign){
	$tmps = explode("-", $strs);

	if ($sign != 0){
		$total = $tmps[2] - $num;
	} else {
		$total = $tmps[2] + $num;
	}
	$tdate = date("Y-m-d", mktime(0, 0, 0, $tmps[1], $total, $tmps[0]));
	return $tdate;
}

//根据日期生成日期路径
function makeDateDir($create_time){
	$str="";
	$tmp=str_replace('-','/',$create_time);
	$str=substr($tmp,0,10);
	return $str."/";
}

function ConventDate($dates){
	$tmp=explode(" ",$dates);
	return $tmp[0];
}

function ConventTime($dates){
	$tmp=explode(" ",$dates);
	return $tmp[1];
}

/*******************************变量处理*******************************/

function register_globals_off(){
	if (!ini_get('register_globals')){
		return;
	}
	// Might want to change this perhaps to a nicer error
	if (isset ($_REQUEST['GLOBALS']) || isset ($_FILES['GLOBALS'])){
		die('GLOBALS overwrite attempt detected');
	}
	// Variables that shouldn't be unset
	$noUnset = array ('GLOBALS', '_GET', '_POST', '_COOKIE', '_REQUEST', '_SERVER', '_ENV', '_FILES');

	$input = array_merge($_GET, $_POST, $_COOKIE, $_SERVER, $_ENV, $_FILES, isset ($_SESSION) && is_array($_SESSION) ? $_SESSION : array ());

	foreach ($input as $k => $v){
		if (!in_array($k, $noUnset) && isset ($GLOBALS[$k])){
			unset ($GLOBALS[$k]);
		}
	}
}

function register_globals_on(){
	if (!ini_get('register_globals')){
		$superglobals = array ($_SERVER, $_ENV, $_FILES, $_COOKIE, $_POST, $_GET);
		if (isset ($_SESSION)){
			array_unshift($superglobals, $_SESSION);
		}
		foreach ($superglobals as $superglobal){
			extract($superglobal, EXTR_SKIP);
		}
	}
}

function SelectVars($Code){
	$tmpvl = array ();
	foreach ($Code as $key => $vl){
		$tmpvl[$vl['value']] = $vl['label'];
	}
	return $tmpvl;
}

function GetVars($newtype, $vls){
	$tmpvl = '';
	foreach ($newtype as $key => $vl){
		if (preg_match("/{$vls}/", $vl['label'])){
			$tmpvl = $vl['value'];
			break;
		}
	}
	return $tmpvl;
}

function get_int($var){//过滤int型变量
	return intval($var);
}

function get_str($var){//过滤strings型变量
	str_resqlin($var);
	if (!get_magic_quotes_gpc()){    // 判断magic_quotes_gpc是否为打开
		$var = addslashes($var);
	}
	//$var = str_replace("_", "\_", $var);    // 把 '_'过滤掉
	//$var = str_replace("%", "\%", $var);    // 把' % '过滤掉
	$var = htmlspecialchars($var);    // html标记转换
	return $var;
}

function str_resqlin($var){//sql注入替换
	$patterns="/.*\b(\%|\#|\<|\(|\)|\[|\]|or|and|exec|insert|select|delete|union|table|database|create|drop|alter|truncate|update|outfile|load_file|count|chr|mid|master|truncate|char|declare|javascript|script|object|alert|comfirm|iframe)\b.*/isU";
	$replaces = "";
	//return preg_replace($patterns, $replaces, $var);
	preg_match($patterns,$var,$matches);
	if(!empty($matches[0])){
		//errorNote(_ERROR_INVALID_STRINGS);
		exit;	
	}
}
//过滤cookies
function filterCookies(){
	$cookiefilter="\\b(and|or)\\b.{1,6}?(=|>|<|\\bin\\b|\\blike\\b)|\\/\\*.+?\\*\\/|<\\s*script\\b|\\bEXEC\\b|UNION.+?SELECT|UPDATE.+?SET|INSERT\\s+INTO.+?VALUES|(SELECT|DELETE).+?FROM|(CREATE|ALTER|DROP|TRUNCATE)\\s+(TABLE|DATABASE)";
	foreach($_COOKIE as $key=>$value){ 
		StopAttack($key,$value,$cookiefilter);
	}
}
//360sql注入
function StopAttack($StrFiltKey,$StrFiltValue,$ArrFiltReq){  
	if(is_array($StrFiltValue))
	{
	    $StrFiltValue=implode($StrFiltValue);
	}  
	if (preg_match("/".$ArrFiltReq."/is",$StrFiltValue)==1){   
	        //slog("<br><br>操作IP: ".$_SERVER["REMOTE_ADDR"]."<br>操作时间: ".strftime("%Y-%m-%d %H:%M:%S")."<br>操作页面:".$_SERVER["PHP_SELF"]."<br>提交方式: ".$_SERVER["REQUEST_METHOD"]."<br>提交参数: ".$StrFiltKey."<br>提交数据: ".$StrFiltValue);
	       //errorNote ( _ERROR_INVALID_STRINGS );
	       exit;
	}      
}

function filterVar($var){//过滤变量
	if(is_int($var)){	
		$var=get_int($var);
	}elseif(is_string($var)){
		$var=get_str($var);
	}
	return $var;
}

function mergeGlobalVars(&$returns,&$vars){
	if(is_array($vars)){
		if(!empty($var)&&!empty($vars[$var])){
			filterVar($var);
			$returns[$var]=filterVar($vars[$var]);
		}else{
			foreach($vars as $key=>$vl){
				filterVar($key);//过滤参数名
				$returns[$key]=filterVar($vl);
			}
		}
	}	
}

function getGlobalVars($var=''){
	filterCookies();
	$returns = array();
	$_GET=router::setRouterVars($_GET);
	mergeGlobalVars($returns,$_GET);
	mergeGlobalVars($returns,$_POST);
	return $returns;
}

function checkSystemOptionRole($option_id,$option){
	global $Data,$_global;

	$Data->setTable($_global->table->module);
	$sql="where modules_father='{$option_id}' and modules_com='{$option}'";
	$Where="where auto_id='{$option_id}' and modules_com='{$option}'";
	if($Data->FetchNumRows($Where)>0)
	return $option_id;
	else
	return $Data->FetchFieldValue('auto_id',$sql);;

}


/*******************************文件处理*******************************/

// Return file data
function readFromFile($filename, $mode = "rb"){
	$handle = @fopen($filename, $mode);
	@flock($handle, LOCK_EX);
	$data = @fread($handle, filesize($filename));
	@fclose($handle);
	return $data;
}

// Put the data into file
function writeToFile($filename, $data){
	$handle =@ fopen($filename, 'w+');
	flock($handle, LOCK_EX);
	$fettle = @fwrite($handle, $data);
	fclose($handle);
	@chmod($filename,0777);
}

function getFileExt($file){
	$match = '';
	if (preg_match('/.*\..*(\./.*)', $file, $match)){
		echo '<pre>';
		print_r($match);
	}
}


//文件扩展名
function getExt($name){
	$exts = explode('.', $name);
	$count = count($exts);
	if ($count > 1){
		return ($exts[$count -1]);
	}else{
		return ('');
	}
}

//获取文件目录及文件名。
function splictFilePathVars($vl){
	$_record=array();
	$pattern="/\S*(\\\\|\/)\S*/isU";
	preg_match($pattern,$vl, $matches);
	$_srcvl=$vl;
	if(!empty($matches[1])){
		$_tmp=explode($matches[1],$vl);
		$_srcvl=$_tmp[count($_tmp)-1];
		unset($_tmp[count($_tmp)-1]);
		$_newpath=implode('/',$_tmp).'/';
		unset($matches);
	}
	$_record['file']=$_srcvl;
	$_record['path']=$_newpath;
	return $_record;
}


function fckImageThumbMark($_srcfile,$_objfile=''){
	global $config;
	if($config->minfckimg=='1'){
		imageThumbMark($_srcfile,$config->minfckimg_width,$config->minfckimg_height,$_objfile);
	}
}
//缩略图及水印
function imageThumbMark($_srcfile,$_width=0,$_height=0,$_objfile=''){
	global $config;
	
	include_once(_ESBCMS_ROOT."/includes/Upload/image.class.php");
	if(!empty($_width)&&!empty($_height)){
		$_image=new Image($_srcfile,$_objfile);
		if($_image->imagecreatefromfunc && $_image->imagefunc) {

			
			$_image->Thumb ( $_width, $_height );

			if($config->imgmark=='1'){
				$_image->Watermark();
				if(!empty($_objfile)) $_image->Watermark(0,$_objfile);
			}
		}
	}
}

function saveimg($vars, $upobj = SYSTEM_UPLOADS_DIR,$dateDir=true){
	global $config;
	if(!empty($config->uploadImgAllowed)){
		$_allowed=explode("|",$config->uploadImgAllowed);
	}else{
		$_allowed=array ('jpg', 'jpeg', 'gif', 'png','swf');
	}
	$_dateDir=$dateDir?makeDateDir(LONG_DATE):'';
	if (!empty ($_FILES[$vars]['name'])){
		$upload = new Upload($vars, $upobj,$_dateDir,true);
		$upload->setTypes($_allowed);
		$upload->setMaxFileSize($config->uploadImgMaxSize,UPLOAD_SIZE_KBYTES);
		$process = $upload->process();

		if (!$process){
			/*//echo "<script language='javascript'>alert ('".$upload->getMessage()."');</script>";*/
			alert($upload->getMessage(),1);
			return false;
		}

		$_filename=$upload->getFileName();
		$_srcfile=$upobj.$_dateDir.$_filename;


		if(!$config->savecfgimg){
		    $_objfile=SYSTEM_SMALLIMG_DIR.$_dateDir.$_filename;
			_mkdirs(SYSTEM_SMALLIMG_DIR.$_dateDir);

			imageThumbMark($_srcfile,$config->minimg_width,$config->minimg_height,$_objfile);
			//@ImageResize($_srcfile,$config->minimg_width,$config->minimg_height,$_objfile);
			//@WaterImg($_srcfile,'up');
			//@WaterImg($_objfile,'up');
		}
		
		return $_dateDir.$_filename;
	}
}

function savefile($vars,$upobj=SYSTEM_UPLOADS_DIR,$dateDir=true){
	global $config;
	if(!empty($config->uploadAllowed)){
		$_allowed=explode("|",$config->uploadAllowed);
	}else{
		$_allowed=array ('zip','rar','doc','xls','pdf','txt');
	}

	$_dateDir=$dateDir?makeDateDir(LONG_DATE):'';

	if(!empty($_FILES[$vars]['name'])){
		$upload = new Upload($vars, $upobj,$_dateDir,true);
		$upload->setTypes($_allowed);
		$upload->setMaxFileSize($config->uploadMaxSize,UPLOAD_SIZE_MBYTES);
		$process = $upload->process();

		if (!$process){
			/*	//echo "<script language='javascript'>alert ('".$upload->getMessage()."');</script>";*/
			alert($upload->getMessage(),1);
			return false;
		}

		$_filename=$upload->getFileName();
		return $_dateDir.$_filename;
	}
}
//上传稿件http附件
function addHttpUploadFile($path){
	$_record=array();
	foreach ($_FILES as $key => $value){
		if ($_FILES[$key]['tmp_name'] != ""){
			$fileName = $_FILES[$key]['name'];
			$path=mosPathName($path);
			$_oldfile=savefile($key,$path,false);
			if(!is_bool($_oldfile)){
				$_record[$key]['obj']=$_oldfile;
				$_record[$key]['src']=$fileName;
			}else{
				alert('',1);
			}
		}
	}
	return $_record;
}


/*******************************模板表单处理*******************************/

function _makeOption($array,$selected='',$enlabel=false,$value='label'){

	$num = count($array);
	ob_start();
	for($i=0; $i<$num; $i++){

		$_curr = $array[$i];
		$_enlabel = '';
		if($enlabel)$_enlabel = (strlen($_curr['label_en']))?"/".$_curr['label_en']:"";
		?>
		<option value='<?=$_curr[$value]?>' <?=($_curr[$value]==$selected)?'selected':''?>><?=$_curr['label'].$_enlabel?></option>	<?php
	}
	$_res =  ob_get_contents();
	ob_end_clean();
	return $_res;
}

function _makeValueOption($array,$selected='',$enlabel=false){
	$num = count($array);
	ob_start();
	foreach($array as $key=>$vl){

		$_curr = $vl;
		$_enlabel = '';
		if($enlabel)$_enlabel = (strlen($_curr['label_en']))?"/".$_curr['label_en']:"";
		?>
		<option value='<?=$_curr['value']?>' <?=($_curr['value']==$selected)?'selected':''?>><?=$_curr['label'].$_enlabel?></option>	<?php
	}
	$_res =  ob_get_contents();
	ob_end_clean();
	return $_res;
}

function _makeKeyOption($array,$selected=''){
	$num = count($array);
	ob_start();
	foreach($array as $key=>$vl){
	?>
	<option value='<?=$key?>' <?=($key==$selected)?'selected':''?>><?=$vl?></option>
	<?
	}
	$_res =  ob_get_contents();
	ob_end_clean();
	return $_res;
}

function _makeCheckbox($arrray,$name='checkbox',$checked=0){
	$num = count($arrray);

	ob_start();
	for($i=0; $i<$num; $i++){

		$_curr = $arrray[$i];
		$_enlabel = '';
		if(is_array($checked))
		{
			?>
<input type="checkbox" value="<?=$_curr['value']?>" name="<?=$name?>" <?=(in_array($_curr['value'],$checked))?'checked':''?>/><?=$_curr['label']?>
			<? }else{?>
			<input type="checkbox" value="<?=$_curr['value']?>" name="<?=$name?>" /><?=$_curr['label']?>
			<?php }
	}
	$_res =  ob_get_contents();
	ob_end_clean();
	return $_res;
}


function setConfigTemplate($_tpl){
	global $smarty;
	setConfigTemplateSign();
	print $smarty->getHtml($_tpl);
}

function setConfigTemplateSign(){
	global $config,$smarty;
	setTemplateMainPage();
	$_template=new parseTemplate($config->template);
	$_template->getTemplateData();
	$tmptplpath="templates/".$_template->getTemplateTpl()."/";
	$smarty->assign('config',$config);
	$smarty->assign('template_tpl',$config->live_site."/tpl/".$tmptplpath);
}

function setTemplateMainPage(){
	global $config,$seoconfig,$seovars,$_verinfo,$_verdesc,$_settingVerify;
	//$_settingVerify->setOffLine();
	$config->mainpage=setSiteMainPageLink();
	if(!empty($GLOBALS['site']['site_domain'])){
		$_domain=$GLOBALS['site']['site_domain'];
	}else{
		$_domainR=($GLOBALS['site']['site_dir']=='/')?'':'/'.$GLOBALS['site']['site_dir'];
		$_domain=$config->live_site.$_domainR;
	}
	$config->site_main=$_domain."/index.php";
	if(empty($seovars) && !is_array($seovars))//避免静态化时重复执行
		$seovars['site_title']=$config->site_title;
	//foreach($seoconfig as $key=>$vl){$seovars[$vl]=$config->$vl;}
	//	$config->meta_keywords.=$_verdesc;
	//	$config->meta_desc.=$_verdesc;
	//$config->site_copyright.="&nbsp;".$_verinfo;
	if(!strstr($config->upload,$config->live_site) && !empty($config->live_site))//避免动态页面重复执行
		$config->upload=$config->live_site.$config->upload;
	$config->libraries=$config->live_site."/".$config->libraries;
	$config->site_logo="<img src=\"".$config->live_site."/images/".$config->site_logo."\">";
}

/**
	* 替换功能模型SEO设置
	* 
	* @access	public
	* @param	array	vars	 功能数据集新闻.
	* @param	array	seocomvars	 功能SEO替换配置数据.
	* @return	null return.
	*/	
function replaceSeoComVars($vars,$mainVars=array()){
	global $config,$seovars;
	$tmpseo=$seovars;
	$com_module=new com_module();
	$seocomvars=$com_module->getSeoComVars();//获得栏目模块的seo与字段对应关系
	setSeoVars($seocomvars,$vars,$tmpseo);
	if(!empty($vars['modules_com'])) {
		$_com=new $vars['modules_com'];
		if(!empty($mainVars[$_com->_id])){
			$_com->setActTable($_com->tbl);
			$_record=$_com->getDataByWhere("where {$_com->_id}='{$mainVars[$_com->_id]}'",array_values($_com->_seoComVars));
			if(is_array($_record)){//如果存在就覆盖
				setSeoVars($_com->_seoComVars,$_record,$tmpseo);
			}
		}
	}	
}
function setSeoVars($seocomvars,$vars,&$tmpTitle){//seo优化 title meta
	global $config,$seovars;
	foreach($seocomvars as $key=>$vl){
		$_key=$seocomvars[$key];
		$_tmpvl=trim($vars[$_key]);
		if(!empty($_tmpvl)) {
			if($key!='site_title'){$config->$key=$_tmpvl;}else{$config->$key=$_tmpvl."-".$tmpTitle[$key];$tmpTitle[$key]=$config->$key;}
		}
	}
}

function setSiteMainPageLink(){
	global $config;
	if($config->tohtml!='1'){
		$linkstring=$config->live_site."/".$config->html_main;
	}else{
		$linkstring=$config->live_site."/".$config->html_portal.VAR_MAIN.$config->html_ext;
	}
	return $linkstring;
}

function setGetUrlLink($vars,$param=''){
	if(is_array($vars)){
		foreach($vars as $key=>$vl){
			if(is_array($param)) $vl=$param[$vl];
			if((empty($linkstring))){
				$linkstring.="{$key}={$vl}";
			}else{
				$linkstring.="&{$key}={$vl}";
			}
		}
	}
	return $linkstring;
}

function setModuleOptionLink($vars=''){//vars栏目数据
	$_linkstring=$vars['modules_link'];

	if(preg_match('/\#/'.VAR_MAIN,$_linkstring)) $_linkstring=setSiteMainPageLink();
	return $_linkstring;
}

function setComLanguagePackage ($com = '') {//引入前台com模块的语言包
  global $config, $language;
  $_prefix = $config->lang . "/" . $config->lang;
  if (! empty($com)) {
    $_comlangfile=_ESBCMS_ROOT.'/modules/'.'/'.$com.'/'.$config->lang."_".$com.".php";
    if (file_exists($_comlangfile)) {
      include ($_comlangfile);
      $_comlangvar = "lang_" . $com;
      $_comlang = $$_comlangvar;
      if (is_array($_comlang))
        $language = array_merge($language,$_comlang);//模块优先
    }else{
    	$_comlangfile=_ESBCMS_ROOT . '/language/'.$config->lang.'/' . $config->lang."_".$com . ".php";
    	if (file_exists($_comlangfile)) 
      		include ($_comlangfile);
      	$_comlangvar = "lang_" . $com;
      	$_comlang = $$_comlangvar;
      	if (is_array($_comlang))
        	$language = array_merge($language,$_comlang);//模块优先
    }
  } else {
  	$_comlangfile=_ESBCMS_ROOT . '/language/' . $_prefix . ".php";
	if (file_exists($_comlangfile))   
    include ($_comlangfile);
    if(is_array($lang))
   	 $language = $lang;
  }
  return $language;
}

function setModLanguagePackage ($com = '',$_lang) {//引入后台mod模块的语言包
  global $language;
  $_prefix = $_lang . "/" . $_lang;
  if (! empty($com)) {
	$mod_name=str_replace("com","mod",$com);
    $_comlangfile=_ESBCMS_ROOT.'/modules/'.$com.'/'.$_lang."_".$mod_name.".php";
    if (file_exists($_comlangfile)) {
      include ($_comlangfile);
      $_comlangvar = "lang_" . $com;
      $_comlang = $$_comlangvar;
      if (is_array($_comlang))
        $language = array_merge($language,$_comlang);//模块优先
    }
 	} else { 
 	$_comlangfile=_ESBCMS_ROOT . '/language/' . $_prefix . "_admin.php";
	if (file_exists($_comlangfile))   
    include ($_comlangfile);
    if(is_array($lang_cp))
    	$language = $lang_cp;
  }
  return $language;
}


//语言转换
function setLanguageCode($vl,$curCode='GB2312',$objCode='UTF8'){
	$chs=new Chinese($curCode,$objCode,$vl);//gb2312输出
	return $chs->ConvertIT();
}

function replaceMsgVars($txt,$vars){
	preg_match_all('/(?:\$\w+)/', $txt, $matches);
	foreach ($matches as $key=>$vl) {
		foreach($vl as $skey=>$svl){
			$_tmpvl=str_replace('$','',$svl);
			$patterns[$skey]="/".$_tmpvl."/";
			$replacements[$skey]=$vars[$_tmpvl];
		}
	}
	return preg_replace($patterns, $replacements, str_replace('$','',$txt));
}

function getTreeList($var,$tbl='',$publish=0){
	global $Data;
	$_TreeView=new TreeView($Data);
	$_TreeView->setTreeSystem(0);
	if(!empty($tbl)) $_TreeView->setTreeViewTbl($tbl);
	if(!empty($publish)) $_TreeView->setTreeViewPublish($publish);
	$_TreeView->TreeViewVars($var);//栏目树
	return $_TreeView->TreeViewList();
}

//检测数组值为空，$back返回，$except不检测
function varsIsEmpty($vars,$back=0,$except=array()){
	foreach($vars as $key=>$vl){
		if(!in_array($key,$except)){
			if(empty($vl)) {
				alert(_ERROR_INFO_EMPTY,$back);
				exit;
			}
		}
	}
}


/*断点续传下传
* @url: 文件地址(路径地址)
* @filename: 要保存的文件名
*/
function download($url, $filename){
	global $config;
	// 获得文件大小, 防止超过2G的文件, 用sprintf来读
	//if(!file_exists($url)) exit;
	$filesize = sprintf("%u", @filesize($url));
	if (!$filesize) return;

	header("content-type:text/html; charset={$config->charset}");
	header("Content-type:application/octet-stream");  //application/x-msdownload
	//header("Content-type:unknown/unknown;");
	header("Content-disposition: inline; filename=\"".$filename."\"");
	header('Content-transfer-encoding: binary');

	if ($range = getenv('HTTP_RANGE')){  // 当有偏移量的时候，采用206的断点续传头
		$range = explode('=', $range);
		$range = $range[1];
		header("HTTP/1.1 206 Partial Content");
		header("Date: " . gmdate("D, d M Y H:i:s") . " GMT");
		header("Last-Modified: ".gmdate("D, d M Y H:i:s", filemtime($url))." GMT");
		header("Accept-Ranges: bytes");
		header("Content-Length:".($filesize - $range));
		header("Content-Range: bytes ".$range.($filesize-1)."/".$filesize);
		header("Connection: close"." ");
	}
	else {
		header("Content-Length:".$filesize." ");
		$range = 0;
	}
	$fp = fopen($url, 'rb');
	fseek($fp, $range);
	while ($bbsf = fread($fp, 4096)){
		echo $bbsf;
	}
	fclose($fp);
}

//随机验证码
function getRandNumVcode($vl='vcode'){
	srand((double)microtime()*1000000);//播下一个生成随机数字的种子，以方便下面随机数生成的使用
	//生成数字和字母混合的验证码方法
	$ychar="0,1,2,3,4,5,6,7,8,9";
	$list=explode(",",$ychar);
	for($i=0;$i<5;$i++){
		$randnum=rand(0,9);
		$authnum.=$list[$randnum];
	}
	$_SESSION[$vl]=$authnum;
	return $authnum;
}

function chkRandNumVcode($key,$vl){
	$_code=$_SESSION[$key];
	if(($_SESSION[$key]==$vl)&&!empty($_SESSION[$key])){
		$_rs=true;
	}else{
		$_rs=false;
	}
	unset($_SESSION[$key]);
	return $_rs;
}

//发送消息 $vars=array('id'=>60,'mid'=>'2'), id=auto_id,mid为接收者id
function sendAutoMsg($vars,$sign,$replacevars=array()){
	$_msg=new com_msg();

	if(is_array($vars)){
		$_msg->sendUserMsgTpl($vars,$sign);
	}else{
		$id=$vars;
		$_msg->sendSystemMsgTpl($id,$sign);
	}
}

function loadHtmlEditor($filed, $value = '&nbsp;', $param = array(),$type='Basic') {
	using ( 'System.Web.UI.FCKeditor.FCKeditor' );
	$path = ! empty ( $param ['path'] ) ? $param ['path'] : "../";
	$width=!empty($param['width'])?$param['width']:"600";
	$height = ! empty ( $param ['height'] ) ? $param ['height'] : "200";
	$mark = ! empty ( $param ['mark'] ) ? $param ['mark'] : "n";
	
	$oFCKeditor = new FCKeditor ( $filed );
	$oFCKeditor->ToolbarSet = $type;
	$oFCKeditor->Width = $width;
	$oFCKeditor->Height = $height;
	$oFCKeditor->Config ['ImageDlgHideAdvanced'] = true;
	$oFCKeditor->Config ['ImageDlgHideLink'] = true;
	$oFCKeditor->Config ['ImageBrowser'] = false;
	$oFCKeditor->Config ['LinkBrowser'] = false;
	$oFCKeditor->Config ['ImageMark'] = $mark;

	$oFCKeditor->Value = empty($value)?"&nbsp;":$value;
	
	return $oFCKeditor->CreateHtml ();
}


/*******************************URL处理*******************************/

function parseURL($add = array (), $del = array (), $page = ''){
	$_GETS = $_GET;
	if ('' === $page){
		$page = $_SERVER['PHP_SELF'];
		if(ESB_DNSL2==1){
			preg_match("/^(\b(?!www)\w+)\..+$/i",$_SERVER['HTTP_HOST'],$matches);//二级域名处理
			if($matches[1])	$page = str_replace("/".$matches[1], '', $_SERVER['PHP_SELF']);
		}
	}//
	if ($del === ''){
		$_GETS = array ();
	}
	elseif (is_array($del) && count($del)){
		foreach ($del as $key => $value){
			unset ($_GETS[$value]);
		}
	}
	//
	if (is_array($add) && count($add))
	$_GETS = array_merge($_GETS, $add);

	$foreach = 0;
	$newQuery = '';
	if (is_array($_GETS) && count($_GETS)){
		foreach ($_GETS AS $key => $value){
			if (!$foreach){
				$newQuery = '?'.$key.'='.urlencode($value);
				$foreach ++;
			} else {
				$newQuery .= '&'.$key.'='.urlencode($value);
			}
		}
	}

	return ($page.$newQuery);
}

function redirect($url = '',$js=0){
	if ($url == '')
	$url = parseURL();
	if (!headers_sent()&&empty($js)){
		header('location: '.$url);
	} else {
		//print_r(headers_list());
		//exit;
		location($url);
	}
	exit;
}

function location($url){
	echo '<script language="javascript">window.location="'.$url.'"</script>';
	exit;
}

function urlRedirect($back=''){
	if(!empty($back)){
		redirect($back);
	}else{
		echo '<script>history.back(-1)</script>';
	}
	exit;
}

function alert($message,$back=0){
	if(!empty($message))
	echo '<script>alert("'.$message.'")</script>';
	if($back){
		echo '<script>history.back(-1);window.location.reload();</script>';//
		exit;
	}
}
function alert1($message,$back=0){
	if(!empty($message))
	echo '<script>alert("'.$message.'")</script>';
	if($back){
		echo '<script>history.back(-1);</script>';//
		exit;
	}

}
function errorNote($message){
	global $config,$language;
	$_tmp= explode(':', $message);
	$message=$language[trim($_tmp[0])];
	if(!$message){
		$message=$_tmp[0];
	}
	if ($_tmp[1]){
		$message.=':'.$_tmp[1];
	}
	$errString='<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">';
	$errString.="<link href=\"{$config->live_site}/tpl/Err.css\" rel=\"stylesheet\" type=\"text/css\" />\n";
	$errString.="<div id=\"ErrNote\">\n";
	$errString.="<div class=\"ErrTitle\">".$language['_LABEL_GOODALERT']."：</div>\n";
	$errString.="<div class=\"ErrMsg\">{$message}</div>\n";
	$errString.="</div>\n";
	print($errString);
	exit;
}


function setNoteBackUrl($_url){
	$_SESSION['_backUrl']=base64_encode($_url);
}

function urlNote($_msg,$back=0){
	global $config;
	//$_msg=urlencode($_msg);
	$_SESSION['_notemsg']=urlencode($_msg);
	if(isset($_SESSION['_backUrl'])){
		$_url=base64_decode($_SESSION['_backUrl']);
		unset($_SESSION['_backUrl']);
	}else{
		if($back){
			echo '<script>history.back(-1)</script>';
			exit;
		}
		$_url=$config->live_site;
	}
	redirect($_url);
}


//////////////////////////////////////////////////////////////
//张兴忠加入的函数
function getDataFieldValue($table,$id,$findFields='content_name',$fields='auto_id'){
	global $Data,$_global;
	$Data->setTable($table);
	$Where="where {$fields}='{$id}'";
	
	$show=$Data->FetchRow(null,$Where);
	return $show[$findFields];
}
function getDataKeyArrayList($table,$Where,$key='auto_id',$value='content_name',$ext='',$keyPre=''){
	global $Data,$_global;
	$arr=array();
	$Data->setTable($table);
	$rs=$Data->FetchRows(null,$Where);
	if(is_array($rs)){
		foreach($rs as $k=>$show){
			$show[$value]=empty($ext)?$show[$value]:$show[$value].$show[$ext];
			$arr[$keyPre.$show[$key]]=$show[$value];
		}
	}
	return $arr;
}
function getVarsValueArrayList($arr){
	$record=array();
	foreach($arr as $key => $vl){
		$record[$vl['value']]=$vl['label'];
	}
	return $record;
}
function getData($table,$id,$fields='auto_id',$hit=0)//只读取一条数据。
{
	global $Data,$_global;
	$Data->setTable($table);
	$Where="where {$fields}='{$id}'";
	$show=$Data->FetchRow(null,$Where);

	if($hit==1)
	if(isset($show['content_hit'])){
		$arr=array();
		$arr['content_hit']=$show['content_hit'] + 1;
		$Data->Edit($arr,$Where);
	}
	return $show;
}
function getDataByWhere($table,$Where){
	global $Data,$_global;
	$Data->setTable($table);
	$show=$Data->FetchRow(null,$Where);
	return $show;
}
function getExtData($com,$table,$id,$fields='auto_id'){
	global $Data,$_global;
	$tmp=explode('_',$com);
	$FieldStrs=getTableFieldExtStrs($tmp[1],$table);
	$Data->setTable("{$table},`{$_global->table->fieldext_value}`");
	$Where="where {$table}.{$fields}={$_global->table->fieldext_value}.link_id and {$table}.{$fields}='{$id}' group by {$table}.{$fields}";
	$show=$Data->FetchRow("{$table}.*,{$FieldStrs}",$Where);
	return $show;
}
function getTableFieldExtStrs($com,$table=''){
	global $Data,$_global;
	$Where="where content_com='{$com}'";
	$Data->setTable($_global->table->fieldext);
	$rs=$Data->FetchRows(array('content_label'),$Where);
	if(is_array($rs)){
		foreach($rs as $k=>$show){
			$arr[]="
			max( 
			CASE WHEN {$_global->table->fieldext_value}.content_field = '{$show['content_label']}'
			THEN {$_global->table->fieldext_value}.content_value
			ELSE 0 
			END ) AS {$show['content_label']}";
		}
	}
	return implode(',',$arr);
}

function get_rand()
{
	list ($usec, $sec) = explode(' ', microtime());
	return (float) $sec + ((float) $usec * 1000000);
}
function getDataList($table,$Where,$type=false,$pages=10,$Plugins = null)//获取多条数据（包含分页）
{
	global $Data,$_global;
	$arr=array();
	$Data->setTable($table);
	if($type)
	{
		$nums=$Data->FetchNumRows($Where);
		$page = new Page($nums);
		$page->SetPerpage($pages);
		$_beg = $page->GetBeg();
		$_end = $page->GetActualRecord();
		$Where .= " LIMIT {$_beg},{$_end}";
		$arr['data_list']=$Data->FetchRows(null,$Where,$Plugins);
		$arr['pageBar']=$page->GetGetBar();
		$arr['pagenums']=$page->GetTotalPages();
		$arr['nums']=$nums;
		return $arr;
	}
	else
	{
		$arr=$Data->FetchRows(NULL,$Where,$Plugins);
		return $arr;
	}
}

function _mkdirs($path)
{
	$adir = explode('/',$path);
	$dirlist = '';
	$rootdir = array_shift($adir);
	if(($rootdir!='.'||$rootdir!='..')&&!file_exists($rootdir))
	{
		@mkdir($rootdir);
	}

	foreach($adir as $key=>$val)
	{
		if($val!='.'&&$val!='..')
		{
			$dirlist .= "/".$val;
			$dirpath = $rootdir.$dirlist;
			if(!file_exists($dirpath))
			{
				@mkdir($dirpath);
				$oldumask = @umask ( 0 );
				@chmod ( $dirpath, 0777 );
				@umask ( $oldumask );
			}
		}
	}
}

function make_radio($name,$id,$array,$select_value,$space_str){
	$num = count($array);
	ob_start();
	for($i=0; $i<$num; $i++){
	?>
	<input name="<?=$name?>" id="r<?=$id?>" value="<?=$array[$i]["value"]?>" type="radio" <? if($select_value==$array[$i]["value"]) echo "checked" ?>>  <?=$array[$i]["label"]?>	
	<?
	if($i<$num-1) echo $space_str;
	}
	$_res =  ob_get_contents();
	ob_end_clean();
	return $_res;
}
function getNextSelect($value,$table){
	global $Data,$_global;
	$Where="where auto_code like '{$value}____' order by auto_position asc";
	$Data->setTable($table);
	$tmp=array();
	$rs=$Data->FetchRows(null,$Where);
	if(is_array($rs)){
		foreach($rs as $k=>$show){
			$tmp[$k]['name']=$show['content_name'];
			$tmp[$k]['key']=$show['auto_code'];
		}
	}
	return json_encode($tmp);
}
function genRandomString($len=10){ //生成指定位数随机数
		$chars = array("a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k",  
			"l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v",  
			"w", "x", "y", "z", "A", "B", "C", "D", "E", "F", "G",  
			"H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R",  
			"S", "T", "U", "V", "W", "X", "Y", "Z", "0", "1", "2",  
			"3", "4", "5", "6", "7", "8", "9"); 
		$charsLen = count($chars) - 1; 
		shuffle($chars);    // 将数组打乱 
		$output = ""; 
		for ($i=0; $i<$len; $i++){ 
			$output .= $chars[mt_rand(0, $charsLen)]; 
		}  
   	    return $output; 
} 

function eWebEditorPagination($s_Content, $s_CurrPage){
	// 小标题列表，当前页标题，当前页内容
	$s_Titles = "";
	$s_CurrTitle = "";
	$s_CurrContent = $s_Content;

	// 页数：0表示没有分页
	$n_PageCount = 0;

	// 当前页
	$n_CurrPage = 1;

	// 当有分页时，存分页正文和标题的数组，下标从1开始
	//$a_PageContent[] = "";
	//$a_PageTitle[] = "";

	// 正则表达式对象
	// 分离出内容中的CSS样式部分，然后在各页中合并，使各分页的显示效果不变
	// <style>...</style>
	$s_Style = "";
	$s_Pattern = "/(<style[^>]*>(.+?)<\/style>)/is";
	//echo $s_Content;
	if (preg_match_all($s_Pattern, $s_CurrContent, $ms)){
		for ($i=0; $i<count($ms[0]); $i++) {
			$s_Style = "\r\n".$s_Style.$ms[0][$i]."\r\n";
		}
		$s_CurrContent = preg_replace($s_Pattern, "", $s_CurrContent);
	}

	// 使用正则表达式对分页进行处理
	$s_Pattern = "/<!--ewebeditor:page title=\"([^\">]*)\"-->(.+?)<!--\/ewebeditor:page-->/is";
	if (preg_match_all($s_Pattern, $s_CurrContent, $ms)){
		for ($i=0; $i<count($ms[0]); $i++) {
			$n_PageCount = $n_PageCount + 1;
			$a_PageTitle[] = $ms[1][$i];
			$a_PageContent[] = $ms[2][$i];
		}
	}
	if ($n_PageCount == 0){
		// 没有分页
		$s_Titles = "";
		$a_PageContent[] = $s_Content;
	}
	//print_r($a_PageContent);
	//exit;
	return $a_PageContent;

}
       function isMobile(){  
    $useragent=isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';  
    $useragent_commentsblock=preg_match('|\(.*?\)|',$useragent,$matches)>0?$matches[0]:'';        
    function CheckSubstrs($substrs,$text){  
        foreach($substrs as $substr)  
            if(false!==strpos($text,$substr)){  
                return true;  
            }  
            return false;  
    }
    $mobile_os_list=array('Google Wireless Transcoder','Windows CE','WindowsCE','Symbian','Android','armv6l','armv5','Mobile','CentOS','mowser','AvantGo','Opera Mobi','J2ME/        MIDP','Smartphone','Go.Web','Palm','iPAQ');
    $mobile_token_list=array('Profile/MIDP','Configuration/CLDC-','160×160','176×220','240×240','240×320','320×240','UP.Browser','UP.Link','SymbianOS','PalmOS','PocketPC','S        onyEricsson','Nokia','BlackBerry','Vodafone','BenQ','Novarra-Vision','Iris','NetFront','HTC_','Xda_','SAMSUNG-SGH','Wapaka','DoCoMo','iPhone','iPod');  
          
    $found_mobile=CheckSubstrs($mobile_os_list,$useragent_commentsblock) ||  
              CheckSubstrs($mobile_token_list,$useragent);  
          
    if ($found_mobile){  
        return true;  
    }else{  
        return false;  
    }  
}
?>