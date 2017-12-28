<?php

class DeviceYSProtocol {
    const READ_CONFIG_GATE_CMDCODE = 192;
    
    public static function encodeDeviceMsg($msgJsonObj, &$msgLen) {
        
        $cmdCode = $msgJsonObj->cmdCode;
        $propRegion;
        $packBin;
        
        switch ($cmdCode) {
            case self::READ_CONFIG_GATE_CMDCODE:
                echo "\n --- read config ---\n";
                $cmdCode = $msgJsonObj->cmdCode;
                $objType = $msgJsonObj->objType;
                $objID = $msgJsonObj->objID;
                
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
    
    //////////////////// 解码 ////////////////////
    
    
    
    public static function decodeDeviceMsg($msgBin, &$msgCRC) {
        
        $dataArray;
        $cmdFormat = "@13/C1cmdCode";
        $cmdArr = unpack($cmdFormat, $msgBin);
        $cmdCode = $cmdArr["cmdCode"];
        
        switch ($cmdCode) {
            case self::READ_CONFIG_GATE_CMDCODE:
                $cmdArr = self::readConfigDecode($msgBin, $msgCRC);
                break;
        }
        
        return array('data'=>$cmdArr);
        
    }
    
    private static function readConfigDecode($msgBin, &$msgCRC) {
        echo "\n --- device config decode ---\n";
        //读取设备配置信息
        $cmdFormat = "@16/n1cmdRetCode";
        $cmdArr = unpack($cmdFormat, $msgBin);
        $cmdRetCode = $cmdArr["cmdRetCode"];
        
        if ($cmdRetCode == 0) {
            $cmdArr["cmdRetCode"] = $cmdRetCode;
            //正确
            
            $cmdFormat = "@16/".
                "n1cmdRetCode/".
                "C1dataObjType/".
                "C1sliceID/".
                "n1dataObjID/".
                "n1fixLen/".
                "n1extLen/".
                "h16devID/".
                "n1parentID/".
                "n1devType/".
                "a16devName/".
                "n1reserved/".
                "C1staSeq/".
                "C1softVer/".
                "n1startClock/".
                "n1stopClock/".
                "n1crc";
            $cmdArr = unpack($cmdFormat, $msgBin);
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
