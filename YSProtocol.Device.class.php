<?php

class Third_Ys_Devicesdk {
    const DELETE_DEVICE_CMDCODE = 2;
    const IDENTIFY_DEVICE_CMDCODE = 4;
    const EXIT_IDENTIFY_DEVICE_CMDCODE = 5;
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
                    $targetType = $subCmdContent->targetType;
                    $targetSubNum = $subCmdContent->targetSubNum;
                    
                    $subDevNumTemp = $subDevNum<<4; //保留高四位
                    $subCmdCodeTemp = $subCmdCode & 0x0f; //保留低四位
                    $subCmdFirstByte = $subDevNumTemp | $subCmdCodeTemp;
                    
                    $targetSubNumTemp = $targetSubNum<<4;
                    $targetTypeTemp = $targetType & 0x0f;
                    $subCmdSecByte = $targetSubNumTemp | $targetTypeTemp;
                    
                    $subCmdFirstByteBin = pack("C", $subCmdFirstByte);
                    $subCmdSecByteBin = pack("C", $subCmdSecByte);
                    $targetIDBin = pack("n", $targetID);
                    
                    $packBin = $packBin.$subCmdFirstByteBin.$subCmdSecByteBin.$targetIDBin;
                    break;
                case 56:
                    //窗帘帖
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
                    $targetType = $subCmdContent->targetType;
                    $targetSubNum = $subCmdContent->targetSubNum;
                    
                    $subDevNumTemp = $subDevNum<<4; //保留高四位
                    $subCmdCodeTemp = $subCmdCode & 0x0f; //保留低四位
                    $subCmdFirstByte = $subDevNumTemp | $subCmdCodeTemp;
                    
                    $targetSubNumTemp = $targetSubNum<<4;
                    $targetTypeTemp = $targetType & 0x0f;
                    $subCmdSecByte = $targetSubNumTemp | $targetTypeTemp;
                    
                    $subCmdFirstByteBin = pack("C", $subCmdFirstByte);
                    $subCmdSecByteBin = pack("C", $subCmdSecByte);
                    $targetIDBin = pack("n", $targetID);
                    
                    $packBin = $packBin.$subCmdFirstByteBin.$subCmdSecByteBin.$targetIDBin;
                    break;
                case 64:
                    //窗帘控制器
                    $subDevNum = $subCmdContent->subDevNum;
                    $subCmdCode = $subCmdContent->subCmdCode;
                    
                    switch ($subCmdCode) {
                        case 0:
                            //关闭窗帘
                            $subDevNumTemp = $subDevNum<<4; //保留高四位
                            $subCmdCodeTemp = $subCmdCode & 0x0f; //保留低四位
                            $subCmdFirstByte = $subDevNumTemp | $subCmdCodeTemp;
                            
                            $subCmdFirstByteBin = pack("C", $subCmdFirstByte);
                            $subCmdSecByteBin = pack("C", 0);
                            
                            $packBin = $packBin.$subCmdFirstByteBin.$subCmdSecByteBin.$subCmdSecByteBin.$subCmdSecByteBin;
                            break;
                        case 1:
                            //移动到指定位置
                            $subDevNumTemp = $subDevNum<<4; //保留高四位
                            $subCmdCodeTemp = $subCmdCode & 0x0f; //保留低四位
                            $subCmdFirstByte = $subDevNumTemp | $subCmdCodeTemp;
                            
                            $subCmdFirstByteBin = pack("C", $subCmdFirstByte);
                            $subCmdSecByteBin = pack("C", 0);
                            $subCmdThdByteBin = pack("C", 0);
                            $subcmdForthByteBin = pack("C", $subCmdContent->pos);
                            
                            $packBin = $packBin.$subCmdFirstByteBin.$subCmdSecByteBin.$subCmdThdByteBin.$subcmdForthByteBin;
                            break;
                        case 4:
                            //设置或取消最大行程
                            $subDevNumTemp = $subDevNum<<4; //保留高四位
                            $subCmdCodeTemp = $subCmdCode & 0x0f; //保留低四位
                            $subCmdFirstByte = $subDevNumTemp | $subCmdCodeTemp;
                            
                            $subCmdFirstByteBin = pack("C", $subCmdFirstByte);
                            $subCmdSecByteBin = pack("C", 0);
                            $setTemp = $subCmdContent->set;
                            if ($setTemp == 1) {
                                //设置
                                $setTemp = 0xfe;
                            } else {
                                //取消
                                $setTemp = 0x00;
                            }
                            $subCmdThdByteBin = pack("C", $setTemp);
                            $subcmdForthByteBin = pack("C", 0);
                            
                            $packBin = $packBin.$subCmdFirstByteBin.$subCmdSecByteBin.$subCmdThdByteBin.$subcmdForthByteBin;
                            break;
                        case 5:
                            //设置中间行程点
                            $subDevNumTemp = $subDevNum<<4; //保留高四位
                            $subCmdCodeTemp = $subCmdCode & 0x0f; //保留低四位
                            $subCmdFirstByte = $subDevNumTemp | $subCmdCodeTemp;
                            
                            $subCmdFirstByteBin = pack("C", $subCmdFirstByte);
                            
                            $pos1 = $subCmdContent->pos1;
                            $pos2 = $subCmdContent->pos2;
                            $pos3 = $subCmdContent->pos3;
                            
                            $centerPos = 0x00 | ($pos1 << 1) | ($pos2 << 3) | ($pos3 << 5);
                            
                            $subCmdSecByteBin = pack("C", $centerPos);
                            
                            $subCmdThdByteBin = pack("C", 0);
                            $subcmdForthByteBin = pack("C", 0);
                            
                            $packBin = $packBin.$subCmdFirstByteBin.$subCmdSecByteBin.$subCmdThdByteBin.$subcmdForthByteBin;
                            break;
                        case 6:
                            //运行到下一行程点
                            $subDevNumTemp = $subDevNum<<4; //保留高四位
                            $subCmdCodeTemp = $subCmdCode & 0x0f; //保留低四位
                            $subCmdFirstByte = $subDevNumTemp | $subCmdCodeTemp;
                            
                            $subCmdFirstByteBin = pack("C", $subCmdFirstByte);
                            
                            $open = $subCmdContent->open;
                            if ($open == 1) {
                                //开窗帘
                                $open = 0;
                            } else {
                                //
                                $open = 0xfe;
                            }                  
                            
                            $subCmdSecByteBin = pack("C", 0);
                            
                            $subCmdThdByteBin = pack("C", 0);
                            $subcmdForthByteBin = pack("C", $open);
                            
                            $packBin = $packBin.$subCmdFirstByteBin.$subCmdSecByteBin.$subCmdThdByteBin.$subcmdForthByteBin;
                            
                            break;
                        case 7:
                            //停止运行
                        case 10:
                            //强制回零
                        case 11:
                            //电机反向
                            $subDevNumTemp = $subDevNum<<4; //保留高四位
                            $subCmdCodeTemp = $subCmdCode & 0x0f; //保留低四位
                            $subCmdFirstByte = $subDevNumTemp | $subCmdCodeTemp;
                            
                            $subCmdFirstByteBin = pack("C", $subCmdFirstByte);
                            
                            $subCmdSecByteBin = pack("C", 0);
                            
                            $subCmdThdByteBin = pack("C", 0);
                            $subcmdForthByteBin = pack("C", 0);
                            
                            $packBin = $packBin.$subCmdFirstByteBin.$subCmdSecByteBin.$subCmdThdByteBin.$subcmdForthByteBin;
                            
                            break;
                    }
                    
                    break;
                case 96:
                    //门磁
                case 97:
                    //红外
                case 98:
                    //  煤气
                    $subDevNum = $subCmdContent->subDevNum;
                    $subCmdCode = $subCmdContent->subCmdCode;
                    
                    if ($subCmdCode == 2) {
                        //控制
                        $subCmdCode = 1;
                        
                        $subDevNumTemp = $subDevNum<<4; //保留高四位
                        $subCmdCodeTemp = $subCmdCode & 0x0f; //保留低四位
                        $subCmdFirstByte = $subDevNumTemp | $subCmdCodeTemp;
                        
                        $security = $subCmdContent->securityReport;
                        $real = $subCmdContent->realReport;
                        $night = $subCmdContent->nightMode;
                        
                        $real = $real << 1;
                        $night = $night << 2;
                        
                        $subCmdSecByte = $security | $real | $night;
                        
                        $subCmdFirstByteBin = pack("C", $subCmdFirstByte);
                        $subCmdSecByteBin = pack("C", $subCmdSecByte);
                        $reserved = pack("n", 0);
                        
                        $packBin = $packBin.$subCmdFirstByteBin.$subCmdSecByteBin.$reserved;
                    }else {
                        if ($subCmdCode == 1) {
                            //绑定
                            $subCmdCode = 2;
                            
                            
                        } else {
                            //解绑定
                            $subCmdCode = 3;
                        }
                        
                        $targetID = $subCmdContent->targetID;
                        $targetType = $subCmdContent->targetType;
                        $targetSubNum = $subCmdContent->targetSubNum;
                        
                        $subDevNumTemp = $subDevNum<<4; //保留高四位
                        $subCmdCodeTemp = $subCmdCode & 0x0f; //保留低四位
                        $subCmdFirstByte = $subDevNumTemp | $subCmdCodeTemp;
                        
                        $targetSubNumTemp = $targetSubNum<<4;
                        $targetTypeTemp = $targetType & 0x0f;
                        $subCmdSecByte = $targetSubNumTemp | $targetTypeTemp;
                        
                        $subCmdFirstByteBin = pack("C", $subCmdFirstByte);
                        $subCmdSecByteBin = pack("C", $subCmdSecByte);
                        $targetIDBin = pack("n", $targetID);
                        
                        $packBin = $packBin.$subCmdFirstByteBin.$subCmdSecByteBin.$targetIDBin;
                    }
                    
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
    
    /**
     * 删除设备
     * @param unknown $msgJsonObj
     * @param unknown $msgLen
     */
    private static function deleteDevice($msgJsonObj, &$msgLen) {
        echo "\n --- device delete ------- \n";
        
        $cmdCode = $msgJsonObj->cmdCode;
        $objType = $msgJsonObj->objType;

        $deleteArr = $msgJsonObj->data->deleteIDArr;
        $deleteLen = count($deleteArr);
        
        $propRegion = 0x8000;
        $propRegionBin = pack("n", $propRegion);
        $objTypeBin = pack("C", $objType);
        $cmdCodeBin = pack("C", $cmdCode);
        $objIDBin = pack("n",0xfffe);
        $reserved = pack("n",0);
        $deleteLenBin = pack("n", $deleteLen);
        
        $packBin = $propRegionBin.$objTypeBin.$cmdCodeBin.$objIDBin.$reserved.$deleteLenBin;
        for ($i = 0; $i < $deleteLen; $i++) {
            $deleteID = $deleteArr[$i];
            $deleteIDBin = pack("n", $deleteID);
            $packBin .= $deleteIDBin;
        }
        
        $msgLen = 18 + 4 + 2*$deleteLen;
        
        return $packBin;
    }
    
    private static function identifyDevice($msgJsonObj, &$msgLen) {
        echo "\n --- identify device ------- \n";
        
        $cmdCode = $msgJsonObj->cmdCode;
        $objType = $msgJsonObj->objType;
        
        $deviceIDArr = $msgJsonObj->data->deviceIDArr;
        $deviceIDLen = count($deviceIDArr);
        
        if ($deviceIDLen == 1) {
            //单个站点
            $propRegion = 0x8000;
            $propRegionBin = pack("n", $propRegion);
            $objTypeBin = pack("C", $objType);
            $cmdCodeBin = pack("C", $cmdCode);
            $objIDBin = pack("n",$deviceIDArr[0]);
            $reserved = pack("n",0);
            
            $packBin = $propRegionBin.$objTypeBin.$cmdCodeBin.$objIDBin.$reserved;
            
            $msgLen = 18 + 2;
        } else {
            //多个站点
            $propRegion = 0x8000;
            $propRegionBin = pack("n", $propRegion);
            $objTypeBin = pack("C", $objType);
            $cmdCodeBin = pack("C", $cmdCode);
            $objIDBin = pack("n",0xfffe);
            $reserved = pack("n",0);
            $deviceIDLenBin = pack("n", $deviceIDLen);
            
            $packBin = $propRegionBin.$objTypeBin.$cmdCodeBin.$objIDBin.$reserved.$deviceIDLenBin;
            for ($i = 0; $i < $deviceIDLen; $i++) {
                $deviceID = $deviceIDArr[$i];
                $deviceIDBin = pack("n", $deviceID);
                $packBin .= $deviceIDBin;
            }
            
            $msgLen = 18 + 4 + 2*$deviceIDLen;
        }
        
        
        return $packBin;
    }
    
    public static function encodeDeviceMsg($msgJsonObj, &$msgLen) {
        
        $cmdCode = $msgJsonObj->cmdCode;
        $propRegion;
        $packBin;
        
        switch ($cmdCode) {
            
            case self::DELETE_DEVICE_CMDCODE:
                //删除站点
                $packBin = self::deleteDevice($msgJsonObj, $msgLen);
                break;
            case self::IDENTIFY_DEVICE_CMDCODE:
                //站点识别
            case self::EXIT_IDENTIFY_DEVICE_CMDCODE:
                //退出站点识别
                $packBin = self::identifyDevice($msgJsonObj, $msgLen);
                break;
            case self::REMOTE_CONTROL_CMDCODE:
                //遥控
                $packBin = self::remoteControl($msgJsonObj, $msgLen);
                break;
            case self::READ_CONFIG_PROPS_CMDCODE:
                echo "\n --- read devices config ---\n";
                
                $packBin = Third_Ys_Helpersdk::readConfigEncode($msgJsonObj, $msgLen);
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
            case self::DELETE_DEVICE_CMDCODE:
                $cmdArr = Third_Ys_Helpersdk::decodeCommonErrorMsg($msgBin, $msgCRC);
                break;
            case self::IDENTIFY_DEVICE_CMDCODE:
                $cmdArr = Third_Ys_Helpersdk::decodeCommonErrorMsg($msgBin, $msgCRC);
                break;
            case self::EXIT_IDENTIFY_DEVICE_CMDCODE:
                $cmdArr = Third_Ys_Helpersdk::decodeCommonErrorMsg($msgBin, $msgCRC);
                break;
            case self::REMOTE_CONTROL_CMDCODE:
                $cmdArr = self::remoteControlDecode($msgBin, $msgCRC);
                break;
            case self::STATUS_UPLOAD_CMDCODE:
                $cmdArr = self::statusUploadDecode($msgBin, $msgCRC);
                break;
            case self::READ_CONFIG_PROPS_CMDCODE:
//                 $cmdArr = self::readConfigDecode($msgBin, $msgCRC);
                $cmdArr = Third_Ys_Helpersdk::readConfigDecode($msgBin, $msgCRC);
                break;
            case self::LIST_OBJID_CMDCODE:
                $cmdArr = Third_Ys_Helpersdk::listAllObjIDsDecode($msgBin, $msgCRC);
                break;
        }
        
        return array('data'=>$cmdArr);
        
    }
    
    public static function decodeSubCmdBin($msgBin, $offset, $devType) {
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
                        $alarm1 = ($controlB3 & 0x02) >> 1;
                        
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
        
        return $devSubCmdObj;
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
                
                $devSubCmdArr[] = self::decodeSubCmdBin($msgBin, $offset, $devType);
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
    
}
