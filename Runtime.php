<?php
/**
 *  统计耗时
 * Created by: Hiko
 * Date: 14-10-13
 * Time: 下午1:11
 */

class Runtime {
    private $_startTime = 0;
    private $_endTime = 0;

    public function start() {
        $this->_startTime = $this->getMicrotime();
    }

    public function end() {
        $this->_endTime = $this->getMicrotime();
    }

    public function spent() {
        return round(($this->_endTime - $this->_startTime) * 1000, 2);
    }

    private function getMicrotime() {

        list($usec, $sec) = explode(' ', microtime());

        return floatval($usec) + floatval($sec);
    }
}
//
//$runtime = new Runtime();
//$runtime->start();
//$a = 0;
//for($i=0; $i < 8000000; $i++)
//{
//    $a += $i;
//}
//$runtime->end();
//echo '耗时：'.$runtime->spent().'毫秒';