<?php
header('content-type:text/html;charset=utf-8');
/*
*单例模式
 */
class Singleton {
	protected $mysql_connect = null;

	private static $instance = null;

	/**
	 * __construct 定义私有的防止通过类的外面创建对象
	 * @Author   YaoXue
	 * @DateTim  2016-07-30
	 */
	private function __construct(){

		echo '<br/>__construct()还是被调用';
		$this->mysql_connect = @mysql_connect('localhost', 'root', '123456789');
	}

	/**
	 * getSingleton 获得Singleton对象的实例
	 * @Author         YaoXue
	 * @DateTim        2016-07-30
	 * @return  Singleton 返回对象的实例
	 */
	public static function getSingleton(){
		
		/*//方法一 
		 if (self::$instance == null) {
			self::$instance = new DaoMysql();
		}
		return self::$instance;*/
		

		//方法二  推荐使用
		if (!self::$instance instanceof self) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * __destruct 析构函数 断开数据库的连接
	 * @Author   YaoXue
	 * @DateTim  2016-07-30
	 */
	public function __destruct(){
		mysql_close($this->mysql_connect);
	}

	/**
	 * 定义私有权限 防止克隆
	 * @Author         YaoXue
	 * @DateTim        2016-07-30
	 * @return  void
	 */
	private function __clone(){

	}
	
}

/**
 * 测试代码部分
 */

//echo '<pre>';
/**
 * 通过调用类里的静态方法来创建对象的实例
 */
/*
$singleton1 = Singleton::getSingleton();
$singleton2 = Singleton::getSingleton();
$singleton3 = Singleton::getSingleton();

var_dump($singleton1, $singleton2, $singleton3);
*/