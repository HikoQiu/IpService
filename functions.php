<?php
/**
 * Created by: Hiko
 * Date: 14-10-13
 * Time: 下午2:27
 */

/**
 *  通过ip，查询对应的data.long.ip文件
 * @param $ip 要查询的ip
 * @param $arrRIpList 以ip数值命名的文件名组成的数组，降序排序，
 * @return bool|string
 */
function get_file_by_ip($ip, $arrRIpList) {
    $ipLong = ip2long($ip);
    foreach($arrRIpList as $index => $maxIpValue) {
        if($ipLong > $maxIpValue) {
            if($index == 0) {
                return false;
            }

            return 'data.'.$arrRIpList[$index-1].'.ip';
        }
        continue;
    }

    return false;
}

/**
 * 获取某个目录下的ip 值组成的数组
 * @param $dirName
 * @return array
 */
function get_max_ip_value_arr($dirName) {
    $dirHandle = opendir($dirName);
    if(!$dirHandle) {
        return false;
    }

    // 获取所有ip文件名，取其中间的值，构造成数组
    $maxIpList = array();
    while($fileName = readdir($dirHandle)) {
        if($fileName == '.' || $fileName == '..') {
            continue;
        }

        $arrFileName = explode('.', $fileName);
        if(count($arrFileName) != 3) {
            continue;
        }

        $maxIpList[] = $arrFileName[1];
    }
    closedir($dirHandle);

    return $maxIpList;
}

/**
 * 从文件中分析ip
 * @param $ip
 * @param $filePath
 */
function analyse_ip($ip, $filePath) {
    $fileHandle = fopen($filePath, 'r');
    if(!$fileHandle) {
        // exit('Error: fail to open file - '.$ipFilePath);
        return false;
    }

    $ipLong = ip2long($ip);
    $targetLine = '';
    while($dataLine = fgets($fileHandle)) {
        $arrIp = explode('`', $dataLine);
        $fieldsCount = count($arrIp);
        $startValue = trim($arrIp[$fieldsCount-2], "\n");
        $endValue = trim($arrIp[$fieldsCount-1], "\n");

        if($ipLong >= $startValue && $ipLong <= $endValue) {
            $targetLine = $dataLine;
            break;
        }
    }

    if(!trim($targetLine)) {
        // exit('Fail to find target line data.');
        return false;
    }

    $arrIp =  explode('`', $targetLine);
    if(count($arrIp) < 9) {
        return false;
    }

    fclose($fileHandle);

    return array(
        'ip' => $ip,
        'country' => $arrIp[2],
        'country_code' => $arrIp[3],
        'province' => $arrIp[4],
        'city' => $arrIp[5],
        'provider' => $arrIp[6]
    );
}

/**
 * 判断是否为空得文件夹
 * @param $path
 * @return bool
 */
function is_empty_dir($path) {
    $handle = opendir($path);
    if($handle) {
        while($file = readdir($handle)) {
            if($file != '.' && $file != '..') {
                return false;
            }
        }
    }

    return true;
}