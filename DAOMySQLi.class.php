<?php
/**
 * DAOMySQLi类，完成对数据库的各种操作
 */
final class DAOMySQLi{

	private $_host;
	private $_user;
	private $_pwd;
	private $_db;
	private $_port;
	private $_charset;

	//静态成员属性,对象的实例
	private static $_instance;
	//数据库对象
	private $_mySQLi;
	/**
	 * 构造函数
	 * @Author   YaoXue
	 * @DateTim  2016-08-13
	 */
	private function __construct(array $option = array()){
		//初始化数据
		$this->_initOption($option);
		$this->__initMySQLi();
	}
	/**
	 * 初始化MySQLi
	 * @Author         YaoXue
	 * @DateTim        2016-08-13
	 * @return  void
	 */
	public function __initMySQLi(){
		$this->_mySQLi = new Mysqli($this->_host, $this->_user, $this->_pwd, $this->_db, $this->_port);
		//判断数据库是否连接
		if ($this->_mySQLi->connect_errno) {
			echo $this->_mySQLi->connect_error;
		}
		$this->_mySQLi->set_charset($this->_charset);
	}
	/**
	 * 初始化数据库配置信息
	 * @Author         YaoXue
	 * @DateTim        2016-08-13
	 * @param   array  $option    数据库信息
	 * @return  void
	 */
	public function _initOption(array $option = array()){
		$this->_host = isset($option['host']) ? $option['host'] : '';
		$this->_user = isset($option['user']) ? $option['user'] : '';
		$this->_pwd = isset($option['pwd']) ? $option['pwd'] : '';
		$this->_db = isset($option['db']) ? $option['db'] : '';
		$this->_port = isset($option['port']) ? $option['port'] : '';
		$this->_charset = isset($option['charset']) ? $option['charset'] : '';
		
		if ($this->_host === '' || $this->_user === '' || $this->_pwd === '' || $this->_db === '' ||
			$this->_port === '' || $this->_charset === '' ) {
			die('参数有误');
		}

	}
	/**
	 * 获取对象实例方法
	 * @Author         YaoXue
	 * @DateTim        2016-08-13
	 * @return  oject 对象的实例
	 */
	public static function getSingleton(array $option = array()){

		if (!self::$_instance instanceof self) {
			self::$_instance = new self($option);
		}

		return self::$_instance;
	}

	/**
	 * 查询一个字段
	 * @Author         YaoXue
	 * @DateTim        2016-08-13
	 * @param   string $sql       查询语句
	 * @return  array            [description]
	 */
	public function fetchOne($sql=''){
		
		if ($res = $this->_mySQLi->query($sql)) {
			$row = $res->fetch_assoc();
			$res->free();
			
		} else {
			echo '执行失败';
			die('操作错误' . $this->_mySQLi->error);
		}

		
		return $row;
	}

	/**
	 * ...
	 * @Author         YaoXue
	 * @DateTim        2016-08-13
	 * @param   string $sql       [description]
	 * @return  array            [description]
	 */
	public function fetchAll($sql=''){
		
		if ($res = $this->_mySQLi->query($sql)) {
			
			$rows = array();
			while ($row = $res->fetch_assoc()) {
					$rows[] = $row;		
			}

			$res->free();
			
		} else {
			echo '执行失败';
			die('操作错误' . $this->_mySQLi->error);
		}

		return $rows;
	}

	public function myQuery($sql=''){
		$res = $this->_mySQLi->query($sql);
		if (!$res) {
			echo '执行失败';
			die('操作错误' . $this->_mySQLi->error);
		}

		return $res;
	}
	private function __clone(){}


}