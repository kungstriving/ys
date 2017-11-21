<?php

class YSProtocol {
    const GATE_OBJ_TYPE = 0;
    const LIGHT_OBJ_TYPE = 16;
    const LIGHT_BELT_OBJ_TYPE = 17;
    
    /**
     * 根据json消息返回二进制数据
     * 
     * @param unknown $msgJson
     */
    public static function encodeMsg($msgJson) {
        echo "[YSProtocol::encodeMsg] for -- " . $msgJson. "\n\n";
        
        $msgHead = 0xfefefe7e;
        $msgHeadBin = pack("N",$msgHead);
        $msgBin = $msgHeadBin;
        
        $tempMsgBin = bin2hex($msgBin);
        echo "[YSProtocol::encodeMsg] result in HEX[". (strlen($tempMsgBin)/2)."] is ".$tempMsgBin;
        
        return $msgBin;
        
    }
    
    /**
     * 根据二进制数据返回json对象
     * @param unknown $msgBin
     */
    public static function decodeMsg($msgBin) {
        echo "[]"
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

YSProtocol::encodeMsg($testJson);



