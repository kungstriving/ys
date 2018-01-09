<?php

class Third_Ys_Devicesdk {
    const REMOTE_CONTROL_CMDCODE = 16;
    const STATUS_UPLOAD_CMDCODE = 32;
    
    const READ_CONFIG_GATE_CMDCODE = 192;
    const LIST_OBJID_CMDCODE = 195;
    
    const DEVICE_OBJ_TYPE = 4;
    
    ////////////////////// 编码 ////////////////////////////////
    
    private static function remoteControl($msgJsonObj, &$msgLen) {
        echo "\n --- device remote control \n";
        $cmdCode = $msgJsonObj->cmdCode;
        $objType = $msgJsonObj->objType;
        $objID = $msgJsonObj->objID;
        
        //根据objID判断是单站点遥控还是多站点遥控还是广播遥控
        
        if ($objID == 65534) {
            //多站点
        } else if($objID == 65535) {
            //广播
        } else {
            //单个站点
            $propRegion = 0x8000;
            $propRegionBin = pack("n", $propRegion);
            $objTypeBin = pack("C", $objType);
            $cmdCodeBin = pack("C", $cmdCode);
            $objIDBin = pack("n", $objID);
            $reserved2B = pack("n",0);
            
            $subCmdNum = $msgJsonObj->data->subCmdNum;
            $subCmdNumBin = pack("n", $subCmdNum);
            $subCmdArr = $msgJsonObj->data->subCmdArr;
            
            $packBin = $propRegionBin.$objTypeBin.$cmdCodeBin.$objIDBin.$reserved2B
                .$subCmdNumBin;
            
            for ($i=0; $i < $subCmdNum; $i++) {
                $subCmdContent = $subCmdArr[$i];
                //根据子命令的终端类型进行分类操作
                $devType = $subCmdContent->devType;
                
                switch ($devType) {
                    case 16:
                        //色灯
                        $subDevNum = $subCmdContent->subDevNum;
                        $powerOn = $subCmdContent->powerOn;
                        $colorH = $subCmdContent->colorH;
                        $colorS = $subCmdContent->colorS;
                        $colorB = $subCmdContent->colorB;
                        
                        $subDevNumTemp = $subDevNum<<4; //保留高四位
                        $powerOnTemp = $powerOn & 0x0f; //保留低四位
                        $subCmdFirstByte = $subDevNumTemp | $powerOnTemp;
                        
                        $subCmdFirstByteBin = pack("C", $subCmdFirstByte);
                        $colorHBin = pack("C", $colorH);
                        $colorSBin = pack("C", $colorS);
                        $colorBBin = pack("C", $colorB);
                        $packBin = $packBin.$subCmdFirstByteBin.$colorHBin.$colorSBin.$colorBBin;
                        break;
                    case 17:
                        break;
                }
                
            }
            
            $msgLen = 18+4+4*$subCmdNum;
        }
        
        return $packBin;
    }
    
    public static function encodeDeviceMsg($msgJsonObj, &$msgLen) {
        
        $cmdCode = $msgJsonObj->cmdCode;
        $propRegion;
        $packBin;
        
        switch ($cmdCode) {
            case self::REMOTE_CONTROL_CMDCODE:
                //遥控
                $packBin = self::remoteControl($msgJsonObj, $msgLen);
                break;
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
                $packBin = Third_Ys_Helpersdk::listAllObjIDs($msgJsonObj, $msgLen);
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
            case self::REMOTE_CONTROL_CMDCODE:
                $cmdArr = self::remoteControlDecode($msgBin, $msgCRC);
                break;
            case self::STATUS_UPLOAD_CMDCODE:
                $cmdArr = self::statusUploadDecode($msgBin, $msgCRC);
                break;
            case self::READ_CONFIG_GATE_CMDCODE:
                $cmdArr = self::readConfigDecode($msgBin, $msgCRC);
                break;
            case self::LIST_OBJID_CMDCODE:
                $cmdArr = Third_Ys_Helpersdk::listAllObjIDsDecode($msgBin, $msgCRC);
                break;
        }
        
        return array('data'=>$cmdArr);
        
    }
    
    private static function statusUploadDecode($msgBin, &$msgCRC) {
        echo "\n --- status upload decode -----\n";
        
        //状态 告警 上报
        $cmdFormat="@16/n1devNum";
        $cmdArr = unpack($cmdFormat, $msgBin);
        $devNum = $cmdArr["devNum"];
        
        $offset = 18;
        $cmdArrTemp;
        $devCmdArr = array();
        //循环读取所有站点对象信息
        for($i = 0; $i < $devNum; $i++) {
            $devCmdObj = array();
            //读取站点类型
            $cmdFormatTemp = "@".$offset."/n1devID/CdevType/CdevNum/n1devSubCmdNum";
            $cmdArrTemp = unpack($cmdFormatTemp, $msgBin);
            $devCmdObj["devID"] = $cmdArrTemp["devID"];
            $devType = $cmdArrTemp["devType"];
            $devCmdObj["devType"] = $devType;
            $devSubCmdNum = $cmdArrTemp["devSubCmdNum"];
            $devCmdObj["devSubCmdNum"] = $devSubCmdNum;
            $devSubCmdArr = array();
            $offset = $offset + 6;
            for($j = 0; $j < $devSubCmdNum; $j++) {
                $devSubCmdObj = array();
                switch ($devType) {
                    case 16:
                        //色灯
                        $cmdFormatTemp = "@".$offset."/CdevSeqSubCmd/CcolorH/CcolorS/CcolorB";
                        $cmdArrTemp = unpack($cmdFormatTemp, $msgBin);
                        $devSeqSubCmd = $cmdArrTemp["devSeqSubCmd"];
                        $subDevNum = $devSeqSubCmd >> 4;
                        $subCmd = $devSeqSubCmd & 0x0f;
                        
                        $devSubCmdObj["powerOn"] = $subCmd;
                        $devSubCmdObj["colorH"] = $cmdArrTemp["colorH"];
                        $devSubCmdObj["colorS"] = $cmdArrTemp["colorS"];
                        $devSubCmdObj["colorB"] = $cmdArrTemp["colorB"];
                        break;
                }
                
                $devSubCmdArr[] = $devSubCmdObj;
                $offset = $offset + 4;
            }
            
            $devCmdObj["devSubCmdArr"] = $devSubCmdArr;
            
            $devCmdArr[] = $devCmdObj;
        }
        
        $cmdArr["devCmdArr"] = $devCmdArr;
        
        $cmdFormatTemp = "@".$offset."/n1crc";
        $cmdArrTemp = unpack($cmdFormatTemp, $msgBin);
        $cmdArr["crc"] = $cmdArrTemp["crc"];
        $msgCRC = $cmdArrTemp["crc"];
        
        return $cmdArr;
    }
    
    private static function remoteControlDecode($msgBin, &$msgCRC) {
        echo "\n --- remote control decode -----\n";
        
        //遥控
        $cmdFormat="@14/n1objID/n1cmdRetCode/n1crc";
        $cmdArr = unpack($cmdFormat, $msgBin);
        $msgCRC = $cmdArr["crc"];
        return $cmdArr;
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
                $tempDevName = Third_Ys_Helpersdk::decodeUnicodeStr($tempDevName);
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
