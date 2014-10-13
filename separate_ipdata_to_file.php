<?php
/**
 * 把大文件ip.data 切分成多个小文件
 * Created by: Hiko
 * Date: 14-10-11
 * Time: 下午3:38
 */
ini_set('max_execution_time',864000);

// 1.1 设置每个文件的最大行数
$fileLines = 1001;

$ipDataFile='ip.data';
$ipDir = 'ip_dir2';
if(!is_dir($ipDir)) {
    if(!mkdir($ipDir, 0777)) {
        die('Permission deny! no right to mkdir ip_dir.');
    }else {
       chmod($ipDir, 0777);
    };
}

if(!is_empty_dir($ipDir)) {
    die('Error: There are some files in dir - '.$ipDir);
}


// 2.1 计算行数
$res = exec("wc -l {$ipDataFile}");
$arrInfo = array_filter(explode(" ", $res));
list($totalNum, $fileName) = array_values($arrInfo);

$ipHandle = fopen($ipDataFile, 'r');
$lineNum = 0;

$lastLineStr = '';
while($lineStr = fgets($ipHandle)) {
    ++$lineNum;

    $fname = $ipDir."/data.".intval($lineNum/$fileLines).'.ip';

    if($lineStr === false) {
        print('false.');
    }else {
        $fh = fopen($fname, 'a');
        if(!$fh) {
            die('Permission deny! '.$ipDir.' has no right to write new file.');
        }
        fwrite($fh, $lineStr);
        fclose($fh);

        $curLine = $lineNum % $fileLines;

        // 以该文件的ip最大值作为文件名的一部分(最后一个文件179得手动去修改)
        if($curLine == ($fileLines - 1)) {
            $arrName = explode('`', $lineStr);
            $newName = trim($arrName[count($arrName) - 1], "\n");
            rename($fname, $ipDir.'/data.'.$newName.'.ip');
        }
    }

    $lastLineStr = $lineStr;
}

// 最后一个文件
if(file_exists($fname)) {
    $arrName = explode('`', $lastLineStr);
    $newName = trim($arrName[count($arrName) - 1], "\n");
    rename($fname, $ipDir.'/data.'.$newName.'.ip');
}

fclose($ipHandle);


