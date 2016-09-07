<?php

/**
 * LinkTool - A useful library for PHP 
 *
 * @author      Dong Nan <hidongnan@gmail.com>
 * @copyright   (c) Dong Nan http://idongnan.cn All rights reserved.
 * @link        https://github.com/dongnan/LinkTool
 * @license     BSD (http://opensource.org/licenses/BSD-3-Clause)
 */

namespace linktool\session;

/**
 * Session
 *
 * @author DongNan <dongyh@126.com>
 * @date 2015-9-4
 */
abstract class Session implements SessionHandlerInterface, SessionIdInterface {

    /**
     * session过期时间
     * @var int 
     */
    protected $lifetime = 3600;
    protected $sessionName = '';

    /**
     * 打开Session 
     * @access public 
     * @param string $savePath 
     * @param string $sessID
     */
    abstract public function open($savePath, $sessID);

    /**
     * 关闭Session 
     * @access public 
     */
    abstract public function close();

    /**
     * 读取Session 
     * @access public 
     * @param string $sessID 
     */
    abstract public function read($sessID);

    /**
     * 写入Session 
     * @access public 
     * @param string $sessID 
     * @param String $sessData  
     */
    abstract public function write($sessID, $sessData);

    /**
     * 删除Session 
     * @access public 
     * @param string $sessID 
     */
    abstract public function destroy($sessID);

    /**
     * Session 垃圾回收
     * @access public 
     * @param string $sessMaxLifeTime 
     */
    abstract public function gc($sessMaxLifeTime);

    /**
     * 生成 session_id
     */
    public function create_sid() {
        
    }

}
