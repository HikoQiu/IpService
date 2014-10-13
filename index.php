<?php
/**
 * Created by: Hiko
 * Date: 14-10-13
 * Time: 上午10:06
 */
require_once 'Runtime.php';
require_once 'IpService.php';

$findIp = '61.135.169.74'; // 测试的ip

$runtime = new Runtime();
$runtime->start();
$info = IpService::getIpInfo($findIp);
$runtime->end();
var_dump($info);

echo '查询IP: '.$findIp.' 耗时 <span style="color: green;">'.$runtime->spent().'</span> 毫秒';
