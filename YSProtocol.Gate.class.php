<?php

class GateYSProtocol {
    
    //命令码
    //网关命令码
    const LOGIN_LAN_GATE_CMDCODE = 1;
    const LOGIN_SERVER_GATE_CMDCODE = 2;
    const LOGOUT_GATE_CMDCODE = 3;
    const HEARTBEAT_GATE_CMDCODE = 4;
    const SERVER_IDENTIFY_GATE_CMDCODE = 5;
    const SERVER_HEARTBEAT_GATE_CMDCODE = 6;//服务器心跳
    const SEARCH_NEW_DEVS_GATE_CMDCODE = 10;
    const LIST_ALL_DEVS_GATE_CMDCODE = 11;
    
    private static $cmdCode;
    private static $objType = 0;
    private static $objID = 0;
    
    private static function loginLan($msgJsonObj, &$msgLen) {
        
        $propRegion = 0x8000;
        $propRegionBin = pack("n",$propRegion);
        $objTypeBin = pack("C",self::$objType);
        $cmdCodeBin = pack("C",self::$cmdCode);
        $objIDBin = pack("n", self::$objID);
        $reservedBin = pack("n",0);
        $dataBin = pack("a32a16a8",
            $msgJsonObj->data->username,
            $msgJsonObj->data->phoneNum,
            $msgJsonObj->data->gateID);
        $packBin = $propRegionBin.$objTypeBin . $cmdCodeBin
            .$objIDBin.$reservedBin.$dataBin;
        $msgLen = 18+58;
        
        return $packBin;
    }
    
    private static function loginServer($msgJsonObj, &$msgLen) {
        echo "\n -------- login server encode ------------\n";
        $propRegion = 0x8000;
        $propRegionBin = pack("n",$propRegion);
        $objTypeBin = pack("C", self::$objType);
        $cmdCodeBin = pack("C", self::$cmdCode);
        $objIDBin = pack("n", self::$objID);
        $reservedBin = pack("n",0);
        $dataBin = pack("a64a16h16",
            $msgJsonObj->data->username,
            $msgJsonObj->data->phoneNum,
            $msgJsonObj->data->gateID);
        $packBin = $propRegionBin.$objTypeBin.$cmdCodeBin
                    .$objIDBin.$reservedBin.$dataBin;
        $msgLen = 18+90;//18固定+用户名手机网关crc
        
        return $packBin;
    }
    
    private static function logout($msgJsonObj, &$msgLen) {
        echo "\n -------- logout encode ------------\n";
        
        $propRegion = 0x8000;
        $propRegionBin = pack("n", $propRegion);
        $objTypeBin = pack("C", self::$objType);
        $cmdCodeBin = pack("C", self::$cmdCode);
        $objIDBin = pack("n", self::$objID);
        $packBin = $propRegionBin.$objTypeBin.$cmdCodeBin
            .$objIDBin;
        $msgLen = 18+0;
        
        return $packBin;
    }
    
    private static function heartbeat($msgJsonObj, &$msgLen) {
        //手机心跳
        //从0A开始
        echo "\n --------------- hb ----------\n";
        $propRegion = 0x8000;
        $propRegionBin = pack("n", $propRegion);
        $objTypeBin = pack("C", self::$objType);
        $cmdCodeBin = pack("C", self::$cmdCode);
        $objIDBin = pack("n", self::$objID);
        $dataBin = pack("a16n1C1C1C1C1C1C1n1",
            $msgJsonObj->data->phoneNum,
            $msgJsonObj->data->year,
            $msgJsonObj->data->month,
            $msgJsonObj->data->day,
            $msgJsonObj->data->hour,
            $msgJsonObj->data->minute,
            $msgJsonObj->data->second,
            $msgJsonObj->data->weekday,
            $msgJsonObj->data->hb);
        $packBin = $propRegionBin.$objTypeBin.$cmdCodeBin
            .$objIDBin.$dataBin;
        $msgLen = 18+26;//18固定+用户名手机网关crc
        
        return $packBin;
    }
    
    private static function serverHeartbeat($msgJsonObj, &$msgLen) {
        //服务器心跳
        echo "\n --------------- hb server ----------\n";
        $propRegion = 0x8000;
        $propRegionBin = pack("n", $propRegion);
        $objTypeBin = pack("C", self::$objType);
        $cmdCodeBin = pack("C", self::$cmdCode);
        $objIDBin = pack("n", self::$objID);
        $dataBin = pack("a16n1C1C1C1C1C1C1n1",
            $msgJsonObj->data->serverID,
            $msgJsonObj->data->year,
            $msgJsonObj->data->month,
            $msgJsonObj->data->day,
            $msgJsonObj->data->hour,
            $msgJsonObj->data->minute,
            $msgJsonObj->data->second,
            $msgJsonObj->data->weekday,
            $msgJsonObj->data->hb);
        $packBin = $propRegionBin.$objTypeBin.$cmdCodeBin
        .$objIDBin.$dataBin;
        $msgLen = 18+26;//18固定+服务器标识年月日心跳crc
        
        return $packBin;
    }
    
    private static function serverIdentifyGate($msgJsonObj, &$msgLen) {
        //服务器识别网关
        //从0A开始
        echo "\n -------- server identify gate ------------\n";
        
        $propRegion = 0x8000;
        $propRegionBin = pack("n", $propRegion);
        $objTypeBin = pack("C", self::$objType);
        $cmdCodeBin = pack("C", self::$cmdCode);
        $objIDBin = pack("n", self::$objID);
        $reservedBin = pack("n",0);
        $dataBin = pack("a16",$msgJsonObj->data->serverID);
        
        $packBin = $propRegionBin.$objTypeBin.$cmdCodeBin
            .$objIDBin.$reservedBin.$dataBin;
        $msgLen = 18+18;
        
        return $packBin;
    }
    
    private static function searchNewDevs($msgJsonObj, &$msgLen) {
        //0A开始
        echo "\n--- search devs ---\n";
        $propRegion = 0x8000;
        $propRegionBin = pack("n", $propRegion);
        $objTypeBin = pack("C", self::$objType);
        $cmdCodeBin = pack("C", self::$cmdCode);
        $objIDBin = pack("n", self::$objID);
        $lastSecs = pack("n", $msgJsonObj->data->lastSecs);
        $packBin = $propRegionBin.$objTypeBin.$cmdCodeBin
            .$objIDBin.$lastSecs;
        $msgLen = 18+2;
        
        return $packBin;
    }
    
    private static function listAllDevs($msgJsonObj, &$msgLen) {
        echo "\n --- list all devices ---\n";
        $propRegion = 0x8000;
        $propRegionBin = pack("n", $propRegion);
        $objTypeBin = pack("C", self::$objType);
        $cmdCodeBin = pack("C", self::$cmdCode);
        $objIDBin = pack("n", self::$objID);
        $packBin = $propRegionBin.$objTypeBin.$cmdCodeBin
            .$objIDBin;
        $msgLen = 18+0;
        
        return $packBin;
    }
    
    public static function encodeGateMsg($msgJsonObj, &$msgLen) {
        
        self::$cmdCode = $msgJsonObj->cmdCode;
        $propRegion;
        $packBin;
        
        switch (self::$cmdCode) {
            case self::LOGIN_LAN_GATE_CMDCODE:
                //局域网登录
                $packBin = self::loginLan($msgJsonObj, $msgLen);
                break;
            case self::LOGIN_SERVER_GATE_CMDCODE:
                //服务器登录
                $packBin = self::loginServer($msgJsonObj, $msgLen);
                break;
            case self::LOGOUT_GATE_CMDCODE:
                //手机退出登录
                //从0A开始
                $packBin = self::logout($msgJsonObj, $msgLen);
                break;
            case self::HEARTBEAT_GATE_CMDCODE:
                $packBin = self::heartbeat($msgJsonObj, $msgLen);
                break;
            case self::SERVER_HEARTBEAT_GATE_CMDCODE:
                $packBin = self::serverHeartbeat($msgJsonObj, $msgLen);
                break;
            case self::SERVER_IDENTIFY_GATE_CMDCODE:
                $packBin = self::serverIdentifyGate($msgJsonObj, $msgLen);
                break;
            case self::SEARCH_NEW_DEVS_GATE_CMDCODE:
                $packBin = self::searchNewDevs($msgJsonObj, $msgLen);
                break;
            case self::LIST_ALL_DEVS_GATE_CMDCODE:
                $packBin = self::listAllDevs($msgJsonObj, $msgLen);
                break;
        }
        
        return $packBin;
        
    }
    
    //////////////////// 解码 ////////////////////
    
    
    
    public static function decodeGateMsg($msgBin, &$msgCRC) {
        
        $dataArray;
        $cmdFormat = "@13/C1cmdCode";
        $cmdArr = unpack($cmdFormat, $msgBin);
        self::$cmdCode = $cmdArr["cmdCode"];
        
        switch (self::$cmdCode) {
            case self::LOGIN_LAN_GATE_CMDCODE:
                
                $cmdArr = self::loginDecode($msgBin, $msgCRC);
                
                break;
            case self::LOGIN_SERVER_GATE_CMDCODE:
                $cmdArr = self::loginServerDecode($msgBin, $msgCRC);
                break;
            case self::LOGOUT_GATE_CMDCODE:
                //退出登录
                echo "\n --- logout decode ---\n";
                $cmdFormat = "@16/n1cmdRetCode/n1crc";
                $cmdArr = unpack($cmdFormat, $msgBin);
                $msgCRC = $cmdArr["crc"];
                break;
            case self::HEARTBEAT_GATE_CMDCODE:
                //心跳的回应
                echo "\n --- hb decode ---\n";
                $cmdFormat = "@16/n1cmdRetCode/n1crc";
                $cmdArr = unpack($cmdFormat, $msgBin);
                $msgCRC = $cmdArr["crc"];
                
                break;
            case self::SERVER_IDENTIFY_GATE_CMDCODE:
                $cmdArr = self::serverIdentifyDecode($msgBin, $msgCRC);
                break;
            case self::SERVER_HEARTBEAT_GATE_CMDCODE:
                echo "\n --- hb server decode ---\n";
                $cmdFormat = "@16/n1cmdRetCode/n1crc";
                $cmdArr = unpack($cmdFormat, $msgBin);
                $msgCRC = $cmdArr["crc"];
                break;
            case self::SEARCH_NEW_DEVS_GATE_CMDCODE:
                //搜索新设备的回应
                echo "\n --- search new devs decode ---\n";
                $cmdFormat="@16/n1cmdRetCode/n1crc";
                $cmdArr = unpack($cmdFormat, $msgBin);
                $msgCRC = $cmdArr["crc"];
                break;
            case self::LIST_ALL_DEVS_GATE_CMDCODE:
                $cmdArr = self::listAllDevsDecode($msgBin, $msgCRC);
                break;
        }
        
        return array('data'=>$cmdArr);
        
    }
    
    private static function listAllDevsDecode($msgBin, &$msgCRC) {
        echo "\n --- list all devs decode ---\n";
        //服务器列表所有终端的回应
        $cmdFormat = "@16/n1cmdRetCode";
        $cmdArr = unpack($cmdFormat, $msgBin);
        $cmdRetCode = $cmdArr["cmdRetCode"];
        
        if ($cmdRetCode == 0) {
            $cmdArr["cmdRetCode"] = $cmdRetCode;
            //正确，读取站点数量
            $cmdFormat = "@18/n1devNum";
            $cmdArr = unpack($cmdFormat, $msgBin);
            $devNum = $cmdArr["devNum"];
            echo "[debug ---]devnum".$devNum."\n";
            $cmdFormat = "@20/";
            
            for ($i = 0; $i < $devNum; $i++) {
                $cmdFormat = $cmdFormat."n1dev".$i."ID/n1dev".$i."Type/h16dev".$i."MAC/";
            }
            
            $cmdFormat = $cmdFormat."n1crc";
            echo "[debug ---]devnum".$cmdFormat."\n";
            
            $cmdArr = unpack($cmdFormat, $msgBin);
            $cmdArr["devNum"] = $devNum;
            $msgCRC = $cmdArr["crc"];
        } else {
            //错误
            $cmdFormat = "@16/".
                "n1cmdRetCode/".
                "n1crc";
            $cmdArr = unpack($cmdFormat, $msgBin);
            $msgCRC = $cmdArr["crc"];
        }
        
        return $cmdArr;
    }
    
    /**
     * @param msgBin
     */
     private static function serverIdentifyDecode($msgBin, &$msgCRC)
    {
        echo "\n --- server identify decode ---\n";
        //服务器识别网关的回应
        $cmdFormat = "@16/n1cmdRetCode";
        $cmdArr = unpack($cmdFormat, $msgBin);
        $cmdRetCode = $cmdArr["cmdRetCode"];
        if ($cmdRetCode == 0) {
            //正确
            $cmdFormat = "@16/".
                "n1cmdRetCode/".
                "A16serverID/".
                "n1sign/".
                "n1gateFixLen/".
                "n1gateExtLen/".
                "n1protoVer/".
                "h16gateID/".
                "h12gateMAC/".
                "@88/".
                "a16gateName/".
                "n1hbLan/".
                "n1hbWLan/".
                "a68reserved/".
                "n1crc";
            $cmdArr = unpack($cmdFormat, $msgBin);
            $msgCRC = $cmdArr["crc"];
        } else {
            //错误
            $cmdFormat = "@16/".
                "n1cmdRetCode/".
                "a8serverMAC/".
                "n1crc";
            $cmdArr = unpack($cmdFormat, $msgBin);
            $msgCRC = $cmdArr["crc"];
        }
        
        return $cmdArr;
    }

    /**
     * @param msgBin
     */
     private static function loginServerDecode($msgBin, &$msgCRC)
    {
        echo "\n --- login server decode ---\n";
        $cmdFormat = "@16/n1cmdRetCode";
        $cmdArr = unpack($cmdFormat, $msgBin);
        $cmdRetCode = $cmdArr["cmdRetCode"];
        if ($cmdRetCode == 0) {
            //正确
            $cmdFormat = "@16/".
                "n1cmdRetCode/".
                "A64username/".
                "A16phoneNum/".
                "h16gateID/".
                "n1sign/".
                "n1gateFixLen/".
                "n1gateExtLen/".
                "n1protoVer/".
                "h16gateID2/".
                "h12gateMAC/".
                "@160/".
                "A16gateName/".
                "n1hbLan/".
                "n1hbWLan/".
                "@216/".
                "n1crc";
            $cmdArr = unpack($cmdFormat, $msgBin);
            $msgCRC = $cmdArr["crc"];
        } else {
            //错误
            $cmdFormat = "@16/".
                "n1cmdRetCode/".
                "A64username/".
                "A16phoneNum/".
                "h16gateID/".
                "n1crc";
            $cmdArr = unpack($cmdFormat, $msgBin);
            $msgCRC = $cmdArr["crc"];
        }
        
        return $cmdArr;
    }

    /**
     * @param msgBin
     */
     private static function loginDecode($msgBin, &$msgCRC)
    {
        //获取命令返回码
        $cmdFormat = "@16/n1cmdRetCode";
        $cmdArr = unpack($cmdFormat, $msgBin);
        $cmdRetCode = $cmdArr["cmdRetCode"];
        if ($cmdRetCode == 0) {
            //正确
            $cmdFormat = "@16/".
                "n1cmdRetCode/".
                "A32username/".
                "A16phoneNum/".
                "A8gateID/".
                "n1sign/".
                "n1gateFixLen/".
                "n1gateExtLen/".
                "n1protoVer/".
                "A8gateID2/".
                "A6gateMAC/".
                "@128/".
                "A16gateName/".
                "n1hbLan/".
                "n1hbWLan/".
                "@216/".
                "n1crc";
            $cmdArr = unpack($cmdFormat, $msgBin);
            
            $msgCRC = $cmdArr["crc"];
        } else {
            //错误
            $cmdFormat = "@16/".
                "n1cmdRetCode/".
                "a32username/".
                "a16phoneNum/".
                "a8gateID/".
                "n1crc";
            $cmdArr = unpack($cmdFormat, $msgBin);
            $msgCRC = $cmdArr["crc"];
        }
        
        return $cmdArr;
    }

}
