<?php

include 'YSProtocol.Gate.class.php';
include 'YSProtocol.Device.class.php';

class YSProtocol {
    //对象类型
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
                $dataBin = GateYSProtocol::encodeGateMsg($msgJsonObj, $msgLen);
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
                
                $dataArray = GateYSProtocol::decodeGateMsg($msgBinReal, $msgCRC);
                break;
        }
        
        //加入CRC
        $commonArray["crc"] = $msgCRC;
        
        $msgArray = array_merge($commonArray, $dataArray);
        var_dump($msgArray);
        
        $msgJson = json_encode($msgArray);
        
        echo "[YSProtocol::decodeMsg] result in json is " . $msgJson . "\n\n";
        return $msgJson;
    }
    
}


