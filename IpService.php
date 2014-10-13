<?php
/**
 * Created by: Hiko
 * Date: 14-10-13
 * Time: 下午2:26
 */


require_once 'functions.php';

class IpService {
    const DIR_NAME = 'ip_dir';

    public static function getIpInfo($ip) {
        $arrRIpList = get_max_ip_value_arr(self::DIR_NAME);
        rsort($arrRIpList);
        $filename = get_file_by_ip($ip, $arrRIpList);
        if(!$filename) {
            // exit('No ip file matched.');
            return false;
        }

        $ipFilePath = self::DIR_NAME.'/'.$filename;
        return analyse_ip($ip, $ipFilePath);
    }

} 