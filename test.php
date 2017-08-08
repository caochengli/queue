<?php
/**
 * 测试
 * User: ccl
 * Date: 2017/8/8
 * Time: 16:28
 */
include 'config.php';
include 'Queue.php';

$value = isset($_GET['v']) ? htmlspecialchars($_GET['v']) : '';
$data = $value ? $value : '测试队列'.date('YmdHis');

// 1.入队列
$queue = new Queue(array('key' => 'test_key'));
$result = $queue->intoQueue($data);

echo '入队列结果:';
echo '<pre>';
print_r($result);

// 2.出队列
$max_queue_num = 200;
$count = $queue->countQueue();
$count = $count > $max_queue_num ? $max_queue_num : $count;

$rs = array();

if ($count > 0)
{
    for ($i = 0; $i < $count; $i++)
    {
        $tmp = $queue->outQueue();
        $rs[] = $tmp;
    }
}

echo '<pre>';
print_r($rs);
exit;




