<?php

/**
 * LinkTool - A useful library for PHP 
 *
 * @author      Dong Nan <hidongnan@gmail.com>
 * @copyright   (c) Dong Nan http://idongnan.cn All rights reserved.
 * @link        https://github.com/dongnan/LinkTool
 * @license     BSD (http://opensource.org/licenses/BSD-3-Clause)
 */


/**
 * File
 *
 * @author DongNan <dongyh@126.com>
 * @date 2015-9-1
 */
class File extends Log
{

    /**
     * 日志名称
     * @var string 
     */
    protected $name;

    /**
     * 日志文件地址
     * @var string 
     */
    protected $logfile;

    /**
     * 文件句柄
     * @var resource 
     */
    protected $stream;

    /**
     * 记录的最小日志等级
     * @var int 
     */
    protected $minlevel;

    /**
     * 构造函数
     * @param string $name 
     * @param string $path
     * @param int $level
     */
    public function __construct($name, $path, $level = Log::DEBUG)
    {
        $this->name = $name;
        $this->logfile = $path;
        $this->minlevel = $level;
        $dir = dirname($this->logfile);
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
    }

    /**
     * 析构函数
     */
    public function __destruct()
    {
        $this->close();
    }

    /**
     * 关闭文件句柄
     */
    public function close()
    {
        if (is_resource($this->stream)) {
            fclose($this->stream);
        }
        $this->stream = null;
    }

    public function log($level, $message, $data = [])
    {
        //根据最小日志等级记录日志
        if ($level < $this->minlevel) {
            return;
        }
        if (!is_resource($this->stream)) {
            $this->stream = fopen($this->logfile, 'a');
            if (!is_resource($this->stream)) {
                throw new Exception("FILE '{$this->logfile}' OPEN ERROR");
            }
        }
        $datetime = date('Y-m-d H:i:s');
        $timestamp = microtime(true);
        $jsonData = Json::encode($data);
        $levelName = isset(self::$levels[$level]) ? self::$levels[$level] : $level;
        $output = "[{$datetime}] {$timestamp} {$this->name}.{$levelName}: {$message} {$jsonData}" . PHP_EOL;
        fwrite($this->stream, $output);
    }

    public function alert($message, $data = [])
    {
        $this->log(Log::ALERT, $message, $data);
    }

    public function critical($message, $data = [])
    {
        $this->log(Log::CRITICAL, $message, $data);
    }

    public function debug($message, $data = [])
    {
        $this->log(Log::DEBUG, $message, $data);
    }

    public function emergency($message, $data = [])
    {
        $this->log(Log::EMERGENCY, $message, $data);
    }

    public function error($message, $data = [])
    {
        $this->log(Log::ERROR, $message, $data);
    }

    public function info($message, $data = [])
    {
        $this->log(Log::INFO, $message, $data);
    }

    public function notice($message, $data = [])
    {
        $this->log(Log::NOTICE, $message, $data);
    }

    public function warning($message, $data = [])
    {
        $this->log(Log::WARNING, $message, $data);
    }

}
