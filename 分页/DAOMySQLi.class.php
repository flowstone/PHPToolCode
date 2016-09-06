<?php
	
	//开发一个DAOMySQLi类，完成对数据库的各种操作.
	//通常情况下DAOMySQLi是单例模式

	final class DAOMySQLi{
		
		//这里我们定义出该类需要的成员变量
		//说明：在一个类的成员变量，他的名字可以以 _开头, 这是编程风格
		private $_host;
		private $_user;
		private $_pwd;
		private $_db;
		private $_port;
		private $_charset;
		
		//这个变量表示DAOMySQLi一个实例
		private static $_instance;
		//这个变量是 MySQLi对象实例
		private $_mySQLi;

		//构造函数
		private function __construct(array $option = array()){
			//初始化成员变量
			$this->_initOption($option);
			
			//初识化_mySQLi 对象
			$this->_initMySQLi();
	
		}

		//做一个专门完成对_mySQLi变量初识
		private function _initMySQLi(){
			
			//初始化 $this->_mySQLi
			$this->_mySQLi = new MySQLi($this->_host, $this->_user, $this->_pwd, $this->_db, $this->_port);

			//判断我们的对象是否成功获取 
			if($this->_mySQLi->connect_errno){
				die('获取mysqli对象失败 错误信息:' .  $this->_mySQLi->connect_error);
			}

			//设置字符集
			$this->_mySQLi->set_charset($this->_charset);
		}

		//做一个专门用于完成成员变量初识的函数
		private function _initOption(array $option = array()){
			//对我们的成员变量进行初始化的操作.
			
			$this->_host = isset($option['host']) ? $option['host'] : '';
			$this->_user = isset($option['user']) ? $option['user'] : '';
			$this->_pwd = isset($option['pwd']) ? $option['pwd'] : '';
			$this->_db = isset($option['db']) ? $option['db'] : '';
			$this->_port = isset($option['port']) ? $option['port'] : '';
			$this->_charset = isset($option['charset']) ? $option['charset'] : '';

			//判断参数是否正确
			if($this->_host === '' || $this->_user === '' || $this->_pwd === '' || $this->_db === '' || $this->_port === '' || $this->_charset === ''){
				die('参数有误');
			}
		}
	
		//阻止克隆
		private function __clone(){
		}

		//获取对象实例的方法,   我们通过数组传入参数.
		public static function getSingleton(array $option = array()){
			
			if(!self::$_instance instanceof self){
				self::$_instance = new self($option);
			}
			return self::$_instance; 
		}

		//提供一个成员方法，用于返回一条记录
		public function fetchOne($sql){
			
			if($res = $this->_mySQLi->query($sql)){
				$row = $res->fetch_assoc();
				$res->free();
			}else{
				echo '<br> sql 执行失败';
				die('错误信息' . $this->_mySQLi->error);
			}
			return $row;
		}

		//编写一个成员方法，完成查询任务
		public function fetchAll($sql ){
			
			
			//这里我们需要使用$this->_mySQLi 完成查询	
			//先定一个空数组
			$arr = array();

			if($res = $this->_mySQLi->query($sql)){
			
				while($row = $res->fetch_assoc()){
					$arr[] = $row;
				}
				//我们希望查询完后，尽快的释放结果集
				$res->free();
			}else{
				echo '<br> sql执行失败';
				die('错误信息是'. $this->_mySQLi->error);
			}

			return $arr;
			
		}

		//编写一个成员方法，完成dml任务

		public function my_query($sql = ''){
			
			//完成操作.
			$res = $this->_mySQLi->query($sql);
			if(!$res){
				echo '<br> 执行dml操作有误!';
				echo '<br> 错误信息如下:';
				echo $this->_mySQLi->error;
			}
			return $res;
		}

	}
	