<?php
/*********************************************************************************
 * InitPHP 2.0 国产PHP开发框架  Dao-Nosql-Redis
 *-------------------------------------------------------------------------------
 * 版权所有: CopyRight By initphp.com
 * 您可以自由使用该源码，但是在使用过程中，请保留作者信息。尊重他人劳动成果就是尊重自己
 *-------------------------------------------------------------------------------
 * $Author:zhuli
 * $Dtime:2011-10-09
 *
 ***********************************************************************************/
class Redisx {

    private $redis; //redis对象

    private $prefix;//redis的数据键名
    /**
     * 初始化Redis
     * $config = array(
     *  'server' => '127.0.0.1' 服务器
     *  'port'   => '6379' 端口号
     * )
     * @param array $config
     */
    public function __construct() {

        global $_SC;

        $this->redis = new Redis();
        $this->redis->connect($_SC['redis'][0], $_SC['redis'][1]);
        $this->redis->auth($_SC['redis'][2]);

        $this->prefix = '';
        return $this->redis;

    }



    /**
     * 设置值
     * @param string $key KEY名称
     * @param string|array $value 获取得到的数据
     * @param int $timeOut 时间
     */
    public function set($key, $value, $timeOut = 0) {
        $value = json_encode($value, TRUE);
        $_key = $this->prefix.$key ;
        $retRes = $this->redis->set($_key, $value);
        if ($timeOut > 0) $this->redis->setTimeout($_key, $timeOut);
        return $retRes;
    }

    /**
     * 通过KEY获取数据
     * @param string $key KEY名称
     */
    public function get($key) {
        $result = $this->redis->get( $this->prefix.$key );
        return json_decode($result, TRUE);
    }

    /**
     * 删除一条数据
     * @param string $key KEY名称
     */
    public function delete($key) {
        return $this->redis->delete($this->prefix.$key);
    }

    /**
     * 清空数据 ，绝对要慎用
     */
    public function flushAll() {
        return false  ; //默认是关掉
        return $this->redis->flushAll();
    }

    /**
     * 数据入队列
     * @param string $key KEY名称
     * @param string|array $value 获取得到的数据
     * @param bool $right 是否从右边开始入
     */
    public function push($key, $value ,$right = true) {
        $value = json_encode($value ,true);
        $_key = $this->prefix.$key ;

        $return = $right ? $this->redis->rPush($_key, $value) : $this->redis->lPush($_key, $value);
        return $return;
    }

    /**
     * 数据出队列
     * @param string $key KEY名称
     * @param bool $left 是否从左边开始出数据
     */
    public function pop($key , $left = true) {
        $_key = $this->prefix.$key ;
        $val = $left ? $this->redis->lPop($_key) : $this->redis->rPop($_key);
        return json_decode($val);
    }

    /**
     * 检查list类型的长度
     *
     */
    public function len($key){
        $_key = $this->prefix.$key ;
        return $this->redis->llen($_key);
    }

    /**
     * 数据自增
     * @param string $key KEY名称
     */
    public function increment($key) {

        return $this->redis->incr($this->prefix.$key);
    }

    /**
     * 数据自减
     * @param string $key KEY名称
     */
    public function decrement($key) {
        return $this->redis->decr($this->prefix.$key);
    }

    /**
     * key是否存在，存在返回ture
     * @param string $key KEY名称
     */
    public function exists($key) {
        return $this->redis->exists($this->prefix.$key);
    }

    /**
     * 返回redis对象
     * redis有非常多的操作方法，我们只封装了一部分
     * 拿着这个对象就可以直接调用redis自身方法
     */
    public function redis() {
        return $this->redis;
    }
}