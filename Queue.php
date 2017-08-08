<?php
/**
 * 封装redis库
 * User: ccl
 * Date: 2016/9/22
 * Time: 9:55
 */

include 'Redisx.php';

class Queue {

    private $_keys = array(
        'test_key'=> 'ccl_test_key',  // 键值对（调用用key，真实redis键值是value）
    );

    protected $redisx;

    public $_key = '';  // 队列的queue键值

    public function __construct($config = array('key'=>'')){
        $this->redisx = new Redisx;
        $key = isset($config['key']) ? $config['key'] : '';
        $this->_key = $this->_keys[$key];

    }

    //入	队列
    public function intoQueue($data = NULL){
        if(empty($data) || empty($this->_key)){
            return false;
        }
        return $this->redisx->push($this->_key, $data , false);//在队列头部插入新队列
    }

    //出队列
    public function outQueue(){
        return $this->redisx->pop($this->_key,false);//从队列尾部删除并返回一条数据
    }

    //队列长度
    public function countQueue(){
        return $this->redisx->len($this->_key);
    }

    //清除某个队列
    public function flushOne($key) {
        return $this->redisx->delete($this->_key);
    }
}
