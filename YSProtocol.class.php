<?php

class YSProtocol {
    const GATE_OBJ_TYPE = 0;
    const LIGHT_OBJ_TYPE = 16;
    const LIGHT_BELT_OBJ_TYPE = 17;
    
    const LOGIN_LAN_GATE_CMDCODE = 1;
    const LOGIN_SERVER_GATE_CMDCODE = 2;
    const LOGOUT_GATE_CMDCODE = 3;
    const HEARTBEAT_GATE_CMDCODE = 4;
    const SERVER_IDENTIFY_GATE_CMDCODE = 5;
    const SEARCH_NEW_DEVS_GATE_CMDCODE = 10;
    const LIST_ALL_DEVS_GATE_CMDCODE = 11;
    
    /**
     * 根据json消息返回二进制数据
     * 
     * @param unknown $msgJson
     */
    public static function encodeMsg($msgJson) {
        echo "[YSProtocol::encodeMsg] for -- " . $msgJson. "\n\n";
        
        $msgJsonObj = json_decode($msgJson);
        
        $msgHead = 0xfefefe7e;
        $msgHeadBin = pack("N",$msgHead);
        
        $msgLen = 0;
        
        $msgSign = $msgJsonObj->sign;
        $msgSignBin = pack("n", $msgSign);
        $msgID = $msgJsonObj->msgID;
        $msgIDBin = pack("n", $msgID);
        
        $objType = $msgJsonObj->objType;
        
        $dataBin;
        switch ($objType) {
            case YSProtocol::GATE_OBJ_TYPE:
                $dataBin = YSProtocol::encodeGateMsg($msgJsonObj, $msgLen);
                break;
            case YSProtocol::LIGHT_OBJ_TYPE:
                break;
        }
        
        $msgLenBin = pack("n", $msgLen);
        
        $msgCrc = 255;
        $msgCrcBin = pack("n", $msgCrc);
        
        $msgBin = $msgHeadBin.$msgLenBin.$msgSignBin.$msgIDBin
                .$dataBin
                .$msgCrcBin;
        
        $tempMsgBin = bin2hex($msgBin);
        echo "[YSProtocol::encodeMsg] result in HEX[". (strlen($tempMsgBin)/2)."] is ".$tempMsgBin."\n\n";
        
        return $msgBin;
        
    }
    
    /**
     * 根据二进制数据返回json对象
     * @param unknown $msgBin
     * n-双字short
     * C-单字char
     * a-读取几个字符的字符串
     */
    public static function decodeMsg($msgBin) {
        echo "[YSProtocol::decodeMsg] for -- ".bin2hex($msgBin)."\n\n";
        
        $msgJson;
        $msgBinReal = hex2bin($msgBin);
        $commonFormat = "@4/".
                        "n1msgLen/".
                        "n1sign/".
                        "n1msgID/".
                        "@12/".
                        "C1objType/".
                        "C1cmdCode/".
                        "n1objID"
                        ;
        
        $commonArray = unpack($commonFormat, $msgBinReal);
        $objType = $commonArray["objType"];
        
        
        $dataArray;
        $msgCRC;
        switch ($objType) {
            case YSProtocol::GATE_OBJ_TYPE:
                
                $dataArray = YSProtocol::decodeGateMsg($msgBinReal, $msgCRC);
                break;
        }
        
        //加入CRC
        $commonArray["crc"] = $msgCRC;
        
        $msgArray = array_merge($commonArray, $dataArray);
//         $msgArray[""]
        var_dump($msgArray);
        
        $msgJson = json_encode($msgArray);
        
        echo "[YSProtocol::decodeMsg] result in json is " . $msgJson . "\n\n";
        return $msgJson;
    }
    
    private static function decodeGateMsg($msgBin, &$msgCRC) {
        $dataArray;
        $cmdFormat = "@13/C1cmdCode";
        $cmdArr = unpack($cmdFormat, $msgBin);
        $cmdCode = $cmdArr["cmdCode"];
        
//         echo "===== ".$cmdCode."==========\n";
        
        switch ($cmdCode) {
            case YSProtocol::LOGIN_LAN_GATE_CMDCODE:
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
                break;
            case YSProtocol::LOGIN_SERVER_GATE_CMDCODE:
                $cmdFormat = "@16/n1cmdRetCode";
                $cmdArr = unpack($cmdFormat, $msgBin);
                $cmdRetCode = $cmdArr["cmdRetCode"];
                if ($cmdRetCode == 0) {
                    //正确
                    $cmdFormat = "@16/".
                        "n1cmdRetCode/".
                        "a32username/".
                        "a16phoneNum/".
                        "a8gateID/".
                        "n1sign/".
                        "n1gateFixLen/".
                        "n1gateExtLen/".
                        "n1protoVer/".
                        "a8gateID2/".
                        "a6gateMAC/".
                        "@128/".
                        "a16gateName/".
                        "n1hbLan/".
                        "n1hbWLan/".
                        "a68netData/".
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
                break;
            case YSProtocol::LOGOUT_GATE_CMDCODE:
                //退出登录
                $cmdFormat = "@16/n1cmdRetCode/n1crc";
                $cmdArr = unpack($cmdFormat, $msgBin);
                $msgCRC = $cmdArr["crc"];
                break;
            case YSProtocol::HEARTBEAT_GATE_CMDCODE:
                //心跳的回应
                $cmdFormat = "@16/n1cmdRetCode/n1crc";
                $cmdArr = unpack($cmdFormat, $msgBin);
                $msgCRC = $cmdArr["crc"];
                
                break;
            case YSProtocol::SERVER_IDENTIFY_GATE_CMDCODE:
                //服务器识别网关的回应
                $cmdFormat = "@16/n1cmdRetCode";
                $cmdArr = unpack($cmdFormat, $msgBin);
                $cmdRetCode = $cmdArr["cmdRetCode"];
                if ($cmdRetCode == 0) {
                    //正确
                    $cmdFormat = "@16/".
                        "n1cmdRetCode/".
                        "a16serverID/".
                        "n1sign/".
                        "n1gateFixLen/".
                        "n1gateExtLen/".
                        "n1protoVer/".
                        "a8gateID/".
                        "a6gateMAC/".
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
                break;
            case YSProtocol::SEARCH_NEW_DEVS_GATE_CMDCODE:
                //服务器搜索新设备的回应
                $cmdFormat="@16/n1cmdRetCode/n1crc";
                $cmdArr = unpack($cmdFormat, $msgBin);
                $msgCRC = $cmdArr["crc"];
                break;
            case YSProtocol::LIST_ALL_DEVS_GATE_CMDCODE:
                //服务器列表所有终端的回应
                $cmdFormat = "@16/n1cmdRetCode";
                $cmdArr = unpack($cmdFormat, $msgBin);
                $cmdRetCode = $cmdArr["cmdRetCode"];
                
                if ($cmdRetCode == 0) {
                    //正确，读取站点数量
                    $cmdFormat = "@18/n1devNum";
                    $cmdArr = unpack($cmdFormat, $msgBin);
                    $devNum = $cmdArr["devNum"];
                    echo "[debug ---]devnum".$devNum."\n";
                    $cmdFormat = "@20/";
                    
                    for ($i = 0; $i < $devNum; $i++) {
                        $cmdFormat = $cmdFormat."n1dev".$i."ID/n1dev".$i."Type/H8dev".$i."MAC/";
                    }
                    
                    $cmdFormat = $cmdFormat."n1crc";
                    
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
                break;
        }
        
        return array('data'=>$cmdArr);
    }
    
    private static function encodeGateMsg($msgJsonObj, &$msgLen) {
        
        $cmdCode = $msgJsonObj->cmdCode;
        $propRegion;
        $objType = 0;
        $objID = 0;
        $packBin;
        switch ($cmdCode) {
            case YSProtocol::LOGIN_LAN_GATE_CMDCODE:
                //局域网登录
                $propRegion = 0x8000;
                $propRegionBin = pack("n",$propRegion);
                $objTypeBin = pack("C",$objType);
                $cmdCodeBin = pack("C",$cmdCode);
                $objIDBin = pack("n", $objID);
                $reservedBin = pack("n",0);
                $dataBin = pack("a32a16a8",
                    $msgJsonObj->data->username,
                    $msgJsonObj->data->phoneNum,
                    $msgJsonObj->data->gateID);
                $packBin = $propRegionBin.$objTypeBin . $cmdCodeBin
                        .$objIDBin.$reservedBin.$dataBin;
                $msgLen = 18+58;
                break;
            case YSProtocol::LOGIN_SERVER_GATE_CMDCODE:
                //服务器登录
                $propRegion = 0x8000;
                $propRegionBin = pack("n",$propRegion);
                $objTypeBin = pack("C", $objType);
                $cmdCodeBin = pack("C", $cmdCode);
                $objIDBin = pack("n", $objID);
                $reservedBin = pack("n",0);
                $dataBin = pack("a32a16a8",
                    $msgJsonObj->data->username,
                    $msgJsonObj->data->phoneNum,
                    $msgJsonObj->data->gateID);
                $packBin = $propRegionBin.$objTypeBin.$cmdCodeBin
                        .$objIDBin.$reservedBin.$dataBin;
                $msgLen = 18+58;//18固定+用户名手机网关crc
                break;
            case YSProtocol::LOGOUT_GATE_CMDCODE:
                //手机退出登录
                //从0A开始
                echo "\n -------- logout ------------\n";
                
                $propRegion = 0x8000;
                $propRegionBin = pack("n", $propRegion);
                $objTypeBin = pack("C", $objType);
                $cmdCodeBin = pack("C", $cmdCode);
                $objIDBin = pack("n", $objID);
                $packBin = $propRegionBin.$objTypeBin.$cmdCodeBin
                    .$objIDBin;
                $msgLen = 18+0;
                break;
            case YSProtocol::HEARTBEAT_GATE_CMDCODE:
                //手机心跳
                //从0A开始
                echo "\n --------------- hb ----------\n";
                $propRegion = 0x8000;
                $propRegionBin = pack("n", $propRegion);
                $objTypeBin = pack("C", $objType);
                $cmdCodeBin = pack("C", $cmdCode);
                $objIDBin = pack("n", $objID);
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
                break;
            case YSProtocol::SERVER_IDENTIFY_GATE_CMDCODE:
                //服务器识别网关
                //从0A开始
                echo "\n -------- identify gate ------------\n";
                
                $propRegion = 0x8000;
                $propRegionBin = pack("n", $propRegion);
                $objTypeBin = pack("C", $objType);
                $cmdCodeBin = pack("C", $cmdCode);
                $objIDBin = pack("n", $objID);
                $reservedBin = pack("n",0);
                $dataBin = pack("a16",$msgJsonObj->data->serverID);
                
                $packBin = $propRegionBin.$objTypeBin.$cmdCodeBin
                    .$objIDBin.$reservedBin.$dataBin;
                $msgLen = 18+18;
                break;
            case YSProtocol::SEARCH_NEW_DEVS_GATE_CMDCODE:
                //0A开始
                echo "\n--- search devs ---\n";
                $propRegion = 0x8000;
                $propRegionBin = pack("n", $propRegion);
                $objTypeBin = pack("C", $objType);
                $cmdCodeBin = pack("C", $cmdCode);
                $objIDBin = pack("n", $objID);
                $lastSecs = pack("n", $msgJsonObj->data->lastSecs);
                $packBin = $propRegionBin.$objTypeBin.$cmdCodeBin
                    .$objIDBin.$lastSecs;
                $msgLen = 18+2;
                break;
            case YSProtocol::LIST_ALL_DEVS_GATE_CMDCODE:
                echo "\n --- list all devices ---\n";
                $propRegion = 0x8000;
                $propRegionBin = pack("n", $propRegion);
                $objTypeBin = pack("C", $objType);
                $cmdCodeBin = pack("C", $cmdCode);
                $objIDBin = pack("n", $objID);
                $packBin = $propRegionBin.$objTypeBin.$cmdCodeBin
                    .$objIDBin;
                $msgLen = 18+0;
                break;
        }
        
        return $packBin;
    }
    
}


