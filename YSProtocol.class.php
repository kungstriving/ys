<?php

class YSProtocol {
    const GATE_OBJ_TYPE = 0;
    const LIGHT_OBJ_TYPE = 16;
    const LIGHT_BELT_OBJ_TYPE = 17;
    
    const LOGIN_LAN_GATE_CMDCODE = 1;
    const LOGIN_WAN_GATE_CMDCODE = 2;
    const LOGOUT_GATE_CMDCODE = 3;
    
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
     */
    public static function decodeMsg($msgBin) {
        echo "[YSProtocol::decodeMsg] for -- ".bin2hex($msgBin)."\n\n";
        
        $msgJson;
        
        $commonFormat = "@4/".
                        "n1msgLen/".
                        "n1sign/".
                        "n1msgID/".
                        "@12/".
                        "C1objType/".
                        "C1cmdCode/".
                        "n1objID"
                        ;
        
        $commonArray = unpack($commonFormat, $msgBin);
        $objType = $commonArray["objType"];
        echo "objtype is ".$objType."\n";
        $dataArray;
        switch ($objType) {
            case YSProtocol::GATE_OBJ_TYPE:
                $dataArray = YSProtocol::decodeGateMsg($msgBin);
                break;
        }
        
        $msgArray = array_merge($commonArray, $dataArray);
        $msgJson = json_encode($msgArray);
        echo "[YSProtocol::decodeMsg] result in json is " . $msgJson . "\n\n";
        return $msgJson;
//         var_dump($commonArray);
    }
    
    private static function decodeGateMsg($msgBin) {
        $cmdCodeFormat = "@13/C1cmdCode";
        $cmdCode = unpack($cmdCodeFormat, $msgBin);
        
        switch ($cmdCode) {
            case YSProtocol::LOGIN_LAN_GATE_CMDCODE:
                echo "---------login response ------\n";
                break;
        }
        
        return array('username'=>"test",'phonenum'=>"13366666666",'gateid'=>"987654321");
    }
    
    private static function encodeGateMsg($msgJsonObj, &$msgLen) {
        
        $cmdCode = $msgJsonObj->cmdCode;
        $propRegion;
        $objType = 0;
        $objID = 0;
        $packBin;
        switch ($cmdCode) {
            case YSProtocol::LOGIN_LAN_GATE_CMDCODE:
                //登录
                $propRegion = 0x8000;
                $propRegionBin = pack("n",$propRegion);
                $objTypeBin = pack("C",$objType);
                $cmdCodeBin = pack("C",$cmdCode);
                $objIDBin = pack("n", $objID);
                $reservedBin = pack("n",0);
                $dataBin = pack("a32a16a8",
                    $msgJsonObj->data->username,
                    $msgJsonObj->data->phonenum,
                    $msgJsonObj->data->gateid);
                $packBin = $propRegionBin.$objTypeBin . $cmdCodeBin
                        .$objIDBin.$reservedBin.$dataBin;
                $msgLen = 18+58;
                break;
        }
        
        
        return $packBin;
    }
    
}


///////////////// 测试 /////////////////////

$testJson = '{
    "sign":1234,
    "msgID":101,
    "objType":0,
    "cmdCode":1,
    "objID":0,
    "data":{
        "username":"test",
        "phonenum":"13366666666",
        "gateid":"987654321"
    },
    "crc":555
}';

$testBin = YSProtocol::encodeMsg($testJson);

YSProtocol::decodeMsg($testBin);

