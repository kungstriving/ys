<?php

class Third_Ys_Devicesdk {
    const REMOTE_CONTROL_CMDCODE = 16;
    const STATUS_UPLOAD_CMDCODE = 32;
    
    const READ_CONFIG_PROPS_CMDCODE = 192;
    const LIST_OBJID_CMDCODE = 195;
    
    const DEVICE_OBJ_TYPE = 4;
    
    ////////////////////// 编码 ////////////////////////////////
    
    public static function encodeSubCmdArr($subCmdArr) {
        
        $subCmdNum = count($subCmdArr);
        $packBin = "";
        for ($i=0; $i < $subCmdNum; $i++) {
            $subCmdContent = $subCmdArr[$i];
            //根据子命令的终端类型进行分类操作
            $devType = $subCmdContent->devType;
            
            switch ($devType) {
                case 16:
                case 17:
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
                case 32:
                case 33:
                    //开关
                case 40:
                case 41:
                    //插座
                case 72:
                    //开关窗控制器
                case 80:
                    //阀门机械臂
                    $subDevNum = $subCmdContent->subDevNum;
                    $powerOn = $subCmdContent->powerOn;
                    
                    $subDevNumTemp = $subDevNum<<4; //保留高四位
                    $powerOnTemp = $powerOn & 0x0f; //保留低四位
                    $subCmdFirstByte = $subDevNumTemp | $powerOnTemp;
                    
                    $subCmdFirstByteBin = pack("C", $subCmdFirstByte);
                    $controlB1 = pack("C", 0);  //保留
                    $controlB2 = pack("C", 0);
                    $controlB3 = pack("C", 0);
                    $packBin = $packBin.$subCmdFirstByteBin.$controlB1.$controlB2.$controlB3;
                    
                    break;
                case 48:
                    //遥控器
                case 52:
                    //开关贴
                    $subDevNum = $subCmdContent->subDevNum;
                    $subCmdCode = $subCmdContent->binding;
                    if ($subCmdCode == 1) {
                        //绑定
                        $subCmdCode = 3;
                    }else if ($subCmdCode == 0) {
                        //解绑定
                        $subCmdCode = 2;
                    }
                    $targetID = $subCmdContent->targetID;
                    $targetSubNum = $subCmdContent->targetSubNum;
                    
                    $subDevNumTemp = $subDevNum<<4; //保留高四位
                    $subCmdCodeTemp = $subCmdCode & 0x0f; //保留低四位
                    $subCmdFirstByte = $subDevNumTemp | $subCmdCodeTemp;
                    
                    $targetSubNumTemp = $targetSubNum<<4;
                    $subCmdSecByte = $targetSubNumTemp | 0x04;
                    
                    $subCmdFirstByteBin = pack("C", $subCmdFirstByte);
                    $subCmdSecByteBin = pack("C", $subCmdSecByte);
                    $targetIDBin = pack("n", $targetID);
                    
                    $packBin = $packBin.$subCmdFirstByteBin.$subCmdSecByteBin.$targetIDBin;
                    
                    break;
            }
            
        }
        
        return $packBin;
    }
    
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
            
            $packBin .= self::encodeSubCmdArr($subCmdArr);
            
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
            case self::READ_CONFIG_PROPS_CMDCODE:
                echo "\n --- read devices config ---\n";
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
            case self::READ_CONFIG_PROPS_CMDCODE:
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
                    case 17:
                        //色灯
                        $cmdFormatTemp = "@".$offset."/CdevSeqSubCmd/CcolorH/CcolorS/CcolorB";
                        $cmdArrTemp = unpack($cmdFormatTemp, $msgBin);
                        $devSeqSubCmd = $cmdArrTemp["devSeqSubCmd"];
                        $subDevNum = $devSeqSubCmd >> 4;
                        $subCmd = $devSeqSubCmd & 0x0f;
                        
                        $devSubCmdObj["subDevNum"] = $subDevNum;
                        $devSubCmdObj["powerOn"] = $subCmd;
                        $devSubCmdObj["colorH"] = $cmdArrTemp["colorH"];
                        $devSubCmdObj["colorS"] = $cmdArrTemp["colorS"];
                        $devSubCmdObj["colorB"] = $cmdArrTemp["colorB"];
                        break;
                    case 32:
                    case 33:
                        //单火线、零火线触摸开关
                    case 40:
                    case 41:
                        //插座
                    case 72:
                        //开关窗控制器
                    case 80:
                        //阀门机械臂
                        $cmdFormatTemp = "@".$offset."/CdevSeqSubCmd/CcontrolB1/CcontrolB2/CcontrolB3";
                        $cmdArrTemp = unpack($cmdFormatTemp, $msgBin);
                        $devSeqSubCmd = $cmdArrTemp["devSeqSubCmd"];
                        $subDevNum = $devSeqSubCmd >> 4;    //子设备号 0=所有灯 1-3=1-3灯
                        $subCmd = $devSeqSubCmd & 0x0f;     //0=关闭 1=开启
                        
                        $devSubCmdObj["subDevNum"] = $subDevNum;
                        $devSubCmdObj["powerOn"] = $subCmd;
                        break;
                    case 96:
                        //门磁
                    case 97:
                        //红外
                    case 98:
                        //煤气
                        
                        $cmdFormatTemp = "@".$offset."/CdevSeqSubCmd/CcontrolB1/CcontrolB2/CcontrolB3";
                        $cmdArrTemp = unpack($cmdFormatTemp, $msgBin);
                        $devSeqSubCmd = $cmdArrTemp["devSeqSubCmd"];
                        $subDevNum = $devSeqSubCmd >> 4;    //子设备号 为1
                        $subCmd = $devSeqSubCmd & 0x0f;     //子命令码 工作模式  1=直接设置 2=解绑定 3=绑定 4=关闭 5=开启
                        
                        switch ($subCmd) {
                            case 1:
                                //工作模式
                                $controlB1 = $cmdArrTemp["controlB1"];
                                $securityReport = $controlB1 & 0x01;
                                $realReport = ($controlB1 & 0x02) >> 1;
                                $nightMode = ($controlB1 & 0x04) >> 2;
                                
                                $devSubCmdObj["subDevNum"] = $subDevNum;
                                $devSubCmdObj["subCmd"] = $subCmd;
                                $devSubCmdObj["securityReport"] = $securityReport;//1=该模式启用 0=未启用
                                $devSubCmdObj["realReport"] = $realReport;//实时通知模式 1=该模式启用 0=未启用
                                $devSubCmdObj["nightMode"] = $nightMode;//夜灯模式1=该模式启用 0=未启用
                                
                                //告警FLAGS
                                $controlB3 = $cmdArrTemp["controlB3"];
                                $lost = $controlB3 & 0x01;  //失联
                                $alarm1 = $controlB3 & 0x02;
                                
                                $devSubCmdObj["lost"] = $lost;  //失联 1=发生 0=未发生
                                $devSubCmdObj["alarm1"] = $alarm1;  //报警1 1=发生 0=未发生
                                
                                break;
                            case 2:
                                //解绑定
                                $binding = 0;   //解绑定
                                $controlB1 = $cmdArrTemp["controlB1"];
                                $targetDevSubNum = $controlB1 >> 4;
                                $targetDevType = $controlB1 & 0x0f;
                                
                                $devSubCmdObj["subDevNum"] = $subDevNum;
                                $devSubCmdObj["binding"] = $binding;
                                $devSubCmdObj["targetDevSubNum"] = $targetDevSubNum;
                                $devSubCmdObj["targetDevType"] = $targetDevType;
                                
                                $cmdFormat = "@".$offset."/CdevSeqSubCmd/CcontrolB1/n1controlB23";
                                $cmdArrTemp = unpack($cmdFormat, $msgBin);
                                
                                $devSubCmdObj["targetObjID"] = $cmdArrTemp["controlB23"];
                                break;
                            case 3:
                                //绑定
                                $binding = 1;   //绑定
                                $controlB1 = $cmdArrTemp["controlB1"];
                                $targetDevSubNum = $controlB1 >> 4;
                                $targetDevType = $controlB1 & 0x0f;
                                
                                $devSubCmdObj["subDevNum"] = $subDevNum;
                                $devSubCmdObj["binding"] = $binding;
                                $devSubCmdObj["targetDevSubNum"] = $targetDevSubNum;
                                $devSubCmdObj["targetDevType"] = $targetDevType;
                                
                                $cmdFormat = "@".$offset."/CdevSeqSubCmd/CcontrolB1/n1controlB23";
                                $cmdArrTemp = unpack($cmdFormat, $msgBin);
                                
                                $devSubCmdObj["targetObjID"] = $cmdArrTemp["controlB23"];
                                break;
                        }
                        break;
                    case 129:
                        //流明监测
                        $cmdFormatTemp = "@".$offset."/CdevSeqSubCmd/CcontrolB1/n1controlB23";
                        $cmdArrTemp = unpack($cmdFormatTemp, $msgBin);
                        $devSeqSubCmd = $cmdArrTemp["devSeqSubCmd"];
                        $subDevNum = $devSeqSubCmd >> 4;    //子设备号 为1
                        $subCmd = $devSeqSubCmd & 0x0f;     //1=流明上报 2=解绑定 3=绑定
                        
                        $devSubCmdObj["subDevNum"] = $subDevNum;
                        
                        if ($subCmd == 1) {
                            //流明上报
                            $devSubCmdObj["lumenReport"] = 1;//流明上报
                            $devSubCmdObj["lumens"] = $cmdArrTemp["controlB23"];
                        } else {
                            if ($subCmd == 2) {
                                //解绑定
                                $devSubCmdObj["binding"] = 0;//解绑定
                            } else if ($subCmd == 3) {
                                //绑定
                                $devSubCmdObj["binding"] = 1;//绑定
                            }
                            $controlB1 = $cmdArrTemp["controlB1"];
                            $targetDevSubNum = $controlB1 >> 4;
                            $targetDevType = $controlB1 & 0x0f;
                            
                            $devSubCmdObj["targetDevSubNum"] = $targetDevSubNum;
                            $devSubCmdObj["targetDevType"] = $targetDevType;
                            $devSubCmdObj["targetObjID"] = $cmdArrTemp["controlB23"];
                        }
                        break;
                    case 130:
                        //温湿度
                        $cmdFormatTemp = "@".$offset."/CdevSeqSubCmd/CcontrolB1/CcontrolB2/CcontrolB3";
                        $cmdArrTemp = unpack($cmdFormatTemp, $msgBin);
                        $devSeqSubCmd = $cmdArrTemp["devSeqSubCmd"];
                        $subDevNum = $devSeqSubCmd >> 4;    //子设备号 为1
                        $subCmd = $devSeqSubCmd & 0x0f;     //1=温湿度上报 2=解绑定 3=绑定
                        
                        $devSubCmdObj["subDevNum"] = $subDevNum;
                        
                        if ($subCmd == 1) {
                            //温湿度上报
                            $devSubCmdObj["tempReport"] = 1;//温湿度上报
                            $devSubCmdObj["humidity"] = $cmdArrTemp["controlB2"];
                            $devSubCmdObj["temperature"] = $cmdArrTemp["controlB3"];
                        } else {
                            if ($subCmd == 2) {
                                //解绑定
                                $devSubCmdObj["binding"] = 0;//解绑定
                            } else if ($subCmd == 3) {
                                //绑定
                                $devSubCmdObj["binding"] = 1;//绑定
                            }
                            
                            $controlB1 = $cmdArrTemp["controlB1"];
                            $targetDevSubNum = $controlB1 >> 4;
                            $targetDevType = $controlB1 & 0x0f;
                            
                            $cmdFormat = "@".$offset."/CdevSeqSubCmd/CcontrolB1/n1controlB23";
                            $cmdArrTemp = unpack($cmdFormat, $msgBin);
                            
                            $devSubCmdObj["targetDevSubNum"] = $targetDevSubNum;
                            $devSubCmdObj["targetDevType"] = $targetDevType;
                            $devSubCmdObj["targetObjID"] = $cmdArrTemp["controlB23"];
                        }
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
        echo "\n --- device read config decode ---\n";
        //读取设备配置信息
        
        //根据单个或同类所有设备来区分
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
                    $cmdFormat = "@16/n1sliceID/".
                        "n1objNum/";
                    $cmdArr = unpack($cmdFormat, $msgBin);
                    $objNum = $cmdArr["objNum"];
                    $sliceID = $cmdArr["sliceID"];
                    
                    $dataObjArr = array();
                    $offset = 20;
                    for ($i = 0; $i < $objNum; $i++) {
                        $dataObj = array();
                        
                        $cmdFormatTemp = "@".$offset."/C1dataObjType/C1reserved/n1dataObjID/n1dataObjFixLen/n1dataObjExtLen";
                        $cmdArrTemp = unpack($cmdFormatTemp, $msgBin);
                        $dataObjType = $cmdArrTemp["dataObjType"];
                        $dataObj["objType"] = $dataObjType;
                        $dataObj["objID"] = $cmdArrTemp["dataObjID"];
                        $fixLen = $cmdArrTemp["dataObjFixLen"];
                        $extLen = $cmdArrTemp["dataObjExtLen"];
                        $offset = $offset + 8;
                        $dataObj["objContent"] = Third_Ys_Helpersdk::decodeObjConfigProps($msgBin, $offset, $dataObjType);
                        
                        $dataObjArr[] = $dataObj;
                        $offset = $offset + $fixLen + $extLen;
                    }
                    
                    $cmdFormat = "@".$offset."/n1crc";
                    $cmdArrTemp = unpack($cmdFormat, $msgBin);
                    
                    $cmdArr["objNum"] = $objNum;
                    $cmdArr["sliceID"] = $sliceID;
                    $cmdArr["dataObjArr"] = $dataObjArr;
                    $cmdArr["crc"] = $cmdArrTemp["crc"];
                    
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
                    $cmdFormat = "@16/n1cmdRetCode/".
                        "n1objNum/";
                    $cmdArr = unpack($cmdFormat, $msgBin);
                    $objNum = $cmdArr["objNum"];
                    $cmdRetCode = $cmdArr["cmdRetCode"];
                    
                    $dataObjArr = array();
                    $offset = 20;
                    
                    for ($i = 0; $i < $objNum; $i++) {
                        
                        $dataObj = array();
                        
                        $cmdFormatTemp = "@".$offset."/C1dataObjType/C1reserved/n1dataObjID/n1dataObjFixLen/n1dataObjExtLen";
                        $cmdArrTemp = unpack($cmdFormatTemp, $msgBin);
                        $dataObjType = $cmdArrTemp["dataObjType"];
                        $dataObj["objType"] = $dataObjType;
                        $dataObj["objID"] = $cmdArrTemp["dataObjID"];
                        $fixLen = $cmdArrTemp["dataObjFixLen"];
                        $extLen = $cmdArrTemp["dataObjExtLen"];
                        $offset = $offset + 8;
                        $dataObj["objContent"] = Third_Ys_Helpersdk::decodeObjConfigProps($msgBin, $offset, $dataObjType);
                        $dataObjArr[] = $dataObj;
                        $offset = $offset + $fixLen + $extLen;
                        
                    }
                    
                    $cmdFormat = "@".$offset."/n1crc";
                    
                    $cmdArrTemp = unpack($cmdFormat, $msgBin);
                    
                    $cmdArr["objNum"] = $objNum;
                    $cmdArr["cmdRetCode"] = $cmdRetCode;
                    $cmdArr["dataObjArr"] = $dataObjArr;
                    $cmdArr["crc"] = $cmdArrTemp["crc"];
                    
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
