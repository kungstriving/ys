<?php

class DeviceYSProtocol {
    const READ_CONFIG_GATE_CMDCODE = 192;
    const LIST_OBJID_CMDCODE = 195;
    
    const DEVICE_OBJ_TYPE = 4;
    
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
                
                
                if (property_exists($msgJsonObj, "sliceSeq")) {
                    
                    //分片
                    $sliceSeq = $msgJsonObj->sliceSeq;
                    
                    $propRegion = 0x8080;
                    $propRegionBin = pack("n", $propRegion);
                    $objTypeBin = pack("C", $objType);
                    $cmdCodeBin = pack("C", $cmdCode);
                    $objIDBin = pack("n", $objID);
                    $sliceSeqBin = pack("n", $sliceSeq);
                    
                    $packBin = $propRegionBin.$objTypeBin.$cmdCodeBin
                        .$objIDBin.$sliceSeqBin;
                    $msgLen = 20+0;
                    
                } else {
                    $propRegion = 0x8000;
                    $propRegionBin = pack("n", $propRegion);
                    $objTypeBin = pack("C", $objType);
                    $cmdCodeBin = pack("C", $cmdCode);
                    $objIDBin = pack("n", $objID);
                    $packBin = $propRegionBin.$objTypeBin.$cmdCodeBin
                        .$objIDBin;
                    $msgLen = 18+0;
                }
                break;
            case self::LIST_OBJID_CMDCODE:
                $packBin = HelperYSProtocol::listAllObjIDs($msgJsonObj, $msgLen);
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
            case self::LIST_OBJID_CMDCODE:
                $cmdArr = HelperYSProtocol::listAllObjIDsDecode($msgBin, $msgCRC);
                break;
        }
        
        return array('data'=>$cmdArr);
        
    }
    
    
    private static function readConfigDecode($msgBin, &$msgCRC) {
        echo "\n --- device config decode ---\n";
        //读取设备配置信息
        
        //根据单个或所有来区分
        $cmdFormat = "@14/n1objID";
        $cmdArr = unpack($cmdFormat, $msgBin);
        $objID = $cmdArr["objID"];
        
        if ($objID == 65535) {
            //65535=同类别所有设备
            
            echo "\n --- device config ALL decode ---\n";
            
            //是否有分片
            $cmdFormat = "@10/n1propRegion";
            $cmdArr = unpack($cmdFormat, $msgBin);
            $propRegion = $cmdArr["propRegion"];
            
            $sliceM = 0x0080 & $propRegion;
            if ($sliceM == 0x0080) {
                //分片
                
                //正确或错误应答
                $sliceE = 0x0040 & $propRegion;
                
                if ($sliceE == 0x0040) {
                    //错误
                    $cmdFormat = "@16/".
                        "n1cmdRetCode/".
                        "n1crc";
                    $cmdArr = unpack($cmdFormat, $msgBin);
                    $msgCRC = $cmdArr["crc"];
                } else {
                    //正确
                    $cmdFormat = "@18/n1objNum";
                    $cmdArr = unpack($cmdFormat, $msgBin);
                    $objNum = $cmdArr["objNum"];
                    
                    $cmdFormat = "@16/n1sliceID/".
                        "n1objNum/";
                    
                    for ($i = 0; $i < $objNum; $i++) {
                        $cmdFormat = $cmdFormat."C1dataObj".$i."Type/C1dataObj".$i."reserved/n1dataObj".$i."ID/n1dataObj".$i."FixLen/n1dataObj".$i."ExtLen/";
                        $cmdArr = unpack($cmdFormat, $msgBin);
                        $objFixLen = $cmdArr["dataObj".$i."FixLen"];
                        $objExtLen = $cmdArr["dataObj".$i."ExtLen"];
                        $cmdFormat = $cmdFormat."h".($objFixLen*2)."dataObj".$i."Fix/";
                        $cmdFormat = $cmdFormat."h".($objExtLen*2)."dataObj".$i."Ext/";
                    }
                    
                    $cmdFormat = $cmdFormat."n1crc";
                    $cmdArr = unpack($cmdFormat, $msgBin);
                    //添加分片标志
                    $sliceT = 0x0010 & $propRegion;
                    if ($sliceT == 0x0010) {
                        //结尾了
                        $cmdArr["sliceT"] = 1;
                    } else {
                        $cmdArr["sliceM"] = 1;
                    }
                    
                    $msgCRC = $cmdArr["crc"];
                }
                
            } else {
                //不分片
                
                //正确或错误应答
                
                $cmdFormat = "@16/n1cmdRetCode/";
                $cmdArr = unpack($cmdFormat, $msgBin);
                $cmdRetCode = $cmdArr["cmdRetCode"];
                
                if ($cmdRetCode == 0) {
                    //正确
                    $cmdFormat = "@18/n1objNum";
                    $cmdArr = unpack($cmdFormat, $msgBin);
                    $objNum = $cmdArr["objNum"];
                    
                    $cmdFormat = "@16/n1cmdRetCode/".
                        "n1objNum/";
                    
                    for ($i = 0; $i < $objNum; $i++) {
                        $cmdFormat = $cmdFormat."C1dataObj".$i."Type/C1dataObj".$i."reserved/n1dataObj".$i."ID/n1dataObj".$i."FixLen/n1dataObj".$i."ExtLen/";
                        $cmdArr = unpack($cmdFormat, $msgBin);
                        $objFixLen = $cmdArr["dataObj".$i."FixLen"];
                        $objExtLen = $cmdArr["dataObj".$i."ExtLen"];
                        $cmdFormat = $cmdFormat."h".($objFixLen*2)."dataObj".$i."Fix/";
                        $cmdFormat = $cmdFormat."h".($objExtLen*2)."dataObj".$i."Ext/";
                    }
                    
                    $cmdFormat = $cmdFormat."n1crc";
                    
                    $cmdArr = unpack($cmdFormat, $msgBin);
                    $msgCRC = $cmdArr["crc"];
                } else {
                    $cmdFormat = "@16/".
                        "n1cmdRetCode/".
                        "n1crc";
                    $cmdArr = unpack($cmdFormat, $msgBin);
                    $msgCRC = $cmdArr["crc"];
                }
                
            }
            
        } else {
            //单个设备
            
            $cmdFormat = "@16/n1cmdRetCode/";
            $cmdArr = unpack($cmdFormat, $msgBin);
            $cmdRetCode = $cmdArr["cmdRetCode"];
            
            if ($cmdRetCode == 0) {
                
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
                    "H32devName/".
                    "n1reserved/".
                    "C1staSeq/".
                    "C1softVer/".
                    "n1startClock/".
                    "n1stopClock/".
                    "n1crc";
                $cmdArr = unpack($cmdFormat, $msgBin);
                
                $tempDevName = $cmdArr["devName"];
                $tempDevName = HelperYSProtocol::decodeUnicodeStr($tempDevName);
                $cmdArr["devName"] = $tempDevName;
                
                $msgCRC = $cmdArr["crc"];
            } else {
                //错误
                $cmdFormat = "@16/".
                    "n1cmdRetCode/".
                    "n1crc";
                $cmdArr = unpack($cmdFormat, $msgBin);
                $msgCRC = $cmdArr["crc"];
            }
            
        }
        
        return $cmdArr;
    }
    
}
