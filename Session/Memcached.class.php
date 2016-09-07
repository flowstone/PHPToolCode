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
 * Memcached
 *
 * @author DongNan <dongyh@126.com>
 * @date 2015-9-4
 */
class Memcached extends Session {

    protected $servers;
    protected $timeout;

    /**
     * Memcached 实例
     * @var \Memcached
     */
    protected $handle = null;

    public function __construct($config = []) {
        $this->sessionName = isset($config['sessionName']) ? $config['sessionName'] : '';
        $this->servers = isset($config['servers']) ? $config['servers'] : ['host' => '127.0.0.1', 'port' => 11211, 'weight' => 1];
        $this->timeout = isset($config['timeout']) ? $config['timeout'] : 1000; //默认为1000ms
        $this->lifetime = isset($config['lifetime']) ? : $this->lifetime;
    }

    public function close() {
        $this->gc(ini_get('session.gc_maxlifetime'));
        $this->handle->close();
        $this->handle = null;
        return true;
    }

    public function destroy($sessID) {
        return $this->handle->delete($this->sessionName . $sessID);
    }

    public function gc($sessMaxLifeTime) {
        return true;
    }

    public function open($savePath, $sessID) {
        $options = [
            \Memcached::OPT_CONNECT_TIMEOUT => $this->timeout,
            \Memcached::OPT_DISTRIBUTION, \Memcached::DISTRIBUTION_CONSISTENT,
        ];
        $this->handle = new \Memcached;
        $this->handle->setOptions($options);
        if (isset($this->servers['host'])) {
            $this->handle->addServer($this->servers['host'], $this->servers['port'], isset($this->servers['weight']) ? $this->servers['weight'] : null);
        } else {
            foreach ($this->servers as $server) {
                $this->handle->addServer($server['host'], $server['port'], isset($server['weight']) ? $server['weight'] : null);
            }
        }
        return true;
    }

    public function read($sessID) {
        return $this->handle->get($this->sessionName . $sessID);
    }

    public function write($sessID, $sessData) {
        return $this->handle->set($this->sessionName . $sessID, $sessData, 0, $this->lifetime);
    }

}
