<?php
class DAOPDO implements I_DAO
{
   private $host;
   private $dbname;
   private $user;
   private $pass;
   private $port;
   private $charset;
   
   private $pdo;
   
   //查询后结果集的数量
   private $resultRows; 
   
   private static $instance;
   //私有的构造方法
   private function __construct($option=array()){
       //初始化数据库配置
       $this->initOptions($option);
       //初始化PDO对象
       $this->initPDO();
   
       
   }
   
   private function initOptions($option){
       $this -> host = isset($option['host']) ? $option['host'] : '';
       $this -> dbname = isset($option['dbname']) ? $option['dbname'] : '';
       $this -> user = isset($option['user']) ? $option['user'] : '';
       $this -> pass = isset($option['pass']) ? $option['pass'] : '';
       $this -> port = isset($option['port']) ? $option['port'] : '';
       $this -> charset = isset($option['charset']) ? $option['charset'] : '';
   }
   
   private function initPDO(){

       $dsn = "mysql:host=$this->host;dbname=$this->dbname;port=$this->port;charset=$this->charset";
       $this->pdo = new PDO($dsn,$this->user,$this->pass);
     
   }
   //防止克隆
   private function __clone(){
       
   }
    /* 
     * 公共的静态方法实例化单例化对象*/
   public static function getSingleton($option=array()){
     if (!self::$instance instanceof  self){
         self::$instance = new self($option);
     }
     return self::$instance;
   }
    /**
     * 查询所有数据的功能
     * @see I_DAO::getAll()
     */
    public function getAll($sql = '')
    {
        // TODO Auto-generated method stub
        $pdo_statement = $this->query($sql);
        $this->resultRows = $pdo_statement->rowCount();
        if ($pdo_statement == false) {
            $error_info = $this->pdo->errorInfo();
            $err_str = "SQL语句错误，具体信息如下" . $error_info[2];
            echo $err_str;
            return false;
        }
        $result = $pdo_statement->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    /**
     * 查询一条数据
     * @see I_DAO::getRow()
     */
    public function getRow($sql = '')
    {
        // TODO Auto-generated method stub
        $pdo_statement = $this->query($sql);
        if ($pdo_statement == false) {
            $error_info = $this->pdo->errorInfo();
            $err_str = "SQL语句错误，具体信息如下" . $error_info[2];
            echo $err_str;
            return false;
        }
        $result = $pdo_statement->fetch(PDO::FETCH_ASSOC);
        return $result;
    }

    /**
     * 查询一个字段的值
     * @see I_DAO::getOne()
     */
    public function getOne($sql = '',$num)
    {
        // TODO Auto-generated method stub
        $pdo_statement = $this->query($sql);
        if ($pdo_statement == false) {
            $error_info = $this->pdo->errorInfo();
            $err_str = "SQL语句错误，具体信息如下" . $error_info[2];
            echo $err_str;
            return false;
        }
        //返回查询字段的值，我们在执行sql语句之前就应该明确查询是哪个字段
        $result = $pdo_statement->fetchColumn();
        return $result;
    }

    /**
     * 执行增删改的功能
     * @see I_DAO::exec()
     */
    public function exec($sql = '')
    {
        // TODO Auto-generated method stub
        $result = $this->pdo->exec($sql);
        if ($result === false) {
            $error_info = $this->pdo->errorInfo();
            $err_str = "SQL语句错误，具体信息如下" . $error_info[2];
            echo $err_str;
            return false;
        }
        return $result;
    }

    /**
     * 查询受影响的记录数
     * @see I_DAO::affectedRows()
     */
    public function resultRows()
    {
        //$pdo_statement = $this->query();
        $result = $this->resultRows;
        return $result;
    }

    /**
     * 查询执行插入操作返回的主键的值
     * @see I_DAO::lastInserId()
     */
    public function lastInserId()
    {
        // TODO Auto-generated method stub
        $result = $this->pdo->lastInsertId();
        return $result;
    }

    /**
     * pdo_statement对象
     * @see I_DAO::query()
     */
    public function query($sql = '')
    {
        // TODO Auto-generated method stub
        return $this->pdo->query($sql);
        
    }

    /**
     * 转义引号、并包裹
     * @see I_DAO::escapeDate()
     */
    public function escapeDate($data = '')
    {
        // TODO Auto-generated method stub
        $result = $this->pdo->quote($data);
        return $result;
    }



}