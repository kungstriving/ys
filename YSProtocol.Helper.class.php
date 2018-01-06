<?php

class HelperYSProtocol {
    public static function decodeUnicodeStr($uniStr) {
        
        $tempStr = chunk_split($uniStr,4,"\u");
        $tempStr = "\u".$tempStr;
        $tempStr = substr($tempStr, 0, (strlen($tempStr) - 2));
        
        $json = '{"str":"'.$tempStr.'"}';
        $arr = json_decode($json,true);
        if(empty($arr)) return '';
        return trim($arr['str']);
    }
    
    public static function listAllObjIDs($msgJsonObj, &$msgLen) {
        
        echo "\n --- list all obj ids ---\n";
        
        $cmdCode = $msgJsonObj->cmdCode;
        $objType = $msgJsonObj->objType;
        
        $propRegion = 0x8000;
        $propRegionBin = pack("n", $propRegion);
        $objTypeBin = pack("C", $objType);
        $cmdCodeBin = pack("C", $cmdCode);
        $objIDBin = pack("n", $msgJsonObj->objID);
        $packBin = $propRegionBin.$objTypeBin.$cmdCodeBin
            .$objIDBin;
        $msgLen = 18+0;
        
        return $packBin;
    }
    
    public static function listAllObjIDsDecode($msgBin, &$msgCRC) {
        
        echo "\n --- list obj ids decode ---\n";
        //列表同类对象ID
        
        $cmdFormat = "@16/n1cmdRetCode";
        $cmdArr = unpack($cmdFormat, $msgBin);
        $cmdRetCode = $cmdArr["cmdRetCode"];
        
        if ($cmdRetCode == 0) {
            $cmdArr["cmdRetCode"] = $cmdRetCode;
            //正确
            //正确，读取对象个数
            $cmdFormat = "@18/n1objNum";
            $cmdArr = unpack($cmdFormat, $msgBin);
            $objNum = $cmdArr["objNum"];
            $cmdFormat = "@20/";
            for ($i = 0; $i < $objNum; $i++) {
                $cmdFormat = $cmdFormat."n1obj".$i."ID/";
            }
            
            $cmdFormat = $cmdFormat."n1crc";
            
            $cmdArr = unpack($cmdFormat, $msgBin);
            $cmdArr["objNum"] = $objNum;
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
}
