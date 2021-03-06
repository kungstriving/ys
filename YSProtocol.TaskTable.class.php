<?php
//任务表
class Third_Ys_Tasktablesdk {
    const CREATE_TASKTABLE_CMDCODE = 1; //创建任务表
    const DELETE_TASKTABLE_CMDCODE = 2; //删除任务表
    const MODIFY_TASKTABLE_CMDCODE = 3; //修改任务表
    const SET_PROP_CMDCODE = 4;         //设置任务表属性
    const ACTIVE_TASKTABLE_CMDCODE = 6; //激活任务表
    const INACTIVE_TASKTABLE_CMDCODE = 7;//去激活任务表
    const START_TASKTABLE_CMDCODE = 16; //召唤任务表
    const STOP_TASKTABLE_CMDCODE = 17;  //停止任务表
    
    const READ_CONFIG_PROPS_CMDCODE = 192;  //读取配置属性信息
    const LIST_OBJID_CMDCODE = 195;     //列表同类对象ID
    
    private static function createTaskTable($msgJsonObj, &$msgLen) {
        echo "\n ------- create tasktable encode ------------\n";
        
        $objType = $msgJsonObj->objType;
        $cmdCode = $msgJsonObj->cmdCode;
        
        $propRegion = 0x8000;
        $propRegionBin = pack("n", $propRegion);
        $objTypeBin = pack("C", $objType);
        $cmdCodeBin = pack("C", $cmdCode);
        $objIDBin = pack("n", $msgJsonObj->objID);
        $fixLen = 144;
        $fixLenBin = pack("n", $fixLen);
        $extLen = 0;
        
        $name = $msgJsonObj->data->name;
        $nameUnicode = Third_Ys_Helpersdk::unicode_encode($name);
        $nameUnicodeBin = pack("a16", $nameUnicode);
        $phone = $msgJsonObj->data->phone;
//         $phoneAscHex = Third_Ys_Helpersdk::ascEncode($phone);
        $phoneBin = pack("a16", $phone);
        $type = $msgJsonObj->data->type;
        $typeBin = pack("C", $type);
        
        switch ($type) {
            case 1:
                //系统情景模式
                $controlFlag = 0x80;
                $controlFlagBin = pack("C", $controlFlag);
                $reserved2B = 0;
                $reserved2BBin = pack("n", $reserved2B);
                $trigger = 0;
                $triggerBin = pack("a92", $trigger);
                $devArrLen = count($msgJsonObj->data->devArr);
                $devArrLenBin = pack("n", $devArrLen);
                $staticSceneLen = count($msgJsonObj->data->staticSceneArr);
                $staticSceneLenBin = pack("n", $staticSceneLen);
                $dynSceneLen = count($msgJsonObj->data->dynSceneArr);
                $dynSceneLenBin = pack("n", $dynSceneLen);
                $reserved10B = 0;
                $reserved10BBin = pack("a10", $reserved10B);
                
                //计算扩展域
                
                $extBin = "";
                //遍历设备
                for ($i = 0; $i < $devArrLen; $i++) {
                    $devCmdObj = $msgJsonObj->data->devArr[$i];
                    $devID = $devCmdObj->devID;
                    $devIDBin = pack("n", $devID);
                    
                    $devSubCmdArr = $devCmdObj->devSubCmdArr;
                    $devSubCmdNum = count($devSubCmdArr);
                    $devSubCmdNumBin = pack("n", $devSubCmdNum);
                    
                    $extBin .= $devIDBin.$devSubCmdNumBin;
                    $extBin .= Third_Ys_Devicesdk::encodeSubCmdArr($devSubCmdArr);
                    
                    $extLen += (2+2+$devSubCmdNum*4);
                }
                
                //遍历静态场景
                for ($i = 0; $i < $staticSceneLen; $i++) {
                    $staticSceneID = $msgJsonObj->data->staticSceneArr[$i];
                    $staticSceneIDBin = pack("n", $staticSceneID);
                    
                    $extBin .= $staticSceneIDBin;
                    
                    $extLen += 2;
                }
                
                //遍历动态场景
                for ($i = 0; $i < $dynSceneLen; $i++) {
                    $dynSceneID = $msgJsonObj->data->dynSceneArr[$i];
                    $dynSceneIDBin = pack("n", $dynSceneID);
                    
                    $extBin .= $dynSceneIDBin;
                    
                    $extLen += 2;
                }
                
                $extLenBin = pack("n", $extLen);
                
                
                $packBin = $propRegionBin.$objTypeBin.$cmdCodeBin.$objIDBin
                    .$fixLenBin.$extLenBin.$nameUnicodeBin.$phoneBin.$typeBin.$controlFlagBin
                    .$reserved2BBin.$triggerBin.$devArrLenBin.$staticSceneLenBin.$dynSceneLenBin
                    .$reserved10BBin.$extBin;
                break;
            case 2:
                //闹钟
                $controlFlag = 0x80;    //使能
                $controlFlagBin = pack("C", $controlFlag);
                $reserved2B = 0;
                $reserved2BBin = pack("n", $reserved2B);
                
                //clock
                $clockSet = $msgJsonObj->data->clockSet;
                $clockFirstByte = 0;
                if ($clockSet->repeat == 1) {
                    //多次
                    $day1 = $clockSet->day1;
                    $day2 = $clockSet->day2;
                    $day3 = $clockSet->day3;
                    $day4 = $clockSet->day4;
                    $day5 = $clockSet->day5;
                    $day6 = $clockSet->day6;
                    $day7 = $clockSet->day7;
                    
                    $clockFirstByte = 0x80 | $day1 | ($day2 << 1) | ($day3 << 2) | ($day4 << 3) | ($day5 << 4) | ($day6 << 5) | ($day7 << 6);
                    
                } else {
                    //单次
                    $weekday = $clockSet->weekday;
                    $clockFirstByte = 0x00 | (0x01 << ($weekday-1));
                }
                
                $clockFirstByteBin = pack("C", $clockFirstByte);
                
                $hour = $clockSet->hour;
                $minute = $clockSet->minute;
                $second = $clockSet->second;
                
                $hourBin = pack("C", $hour);
                $minuteBin = pack("C", $minute);
                $secondBin = pack("C", $second);
                
                $trigger = 0;
                $triggerBin = pack("a88", $trigger);
                $devArrLen = count($msgJsonObj->data->devArr);
                $devArrLenBin = pack("n", $devArrLen);
                $staticSceneLen = count($msgJsonObj->data->staticSceneArr);
                $staticSceneLenBin = pack("n", $staticSceneLen);
                $dynSceneLen = count($msgJsonObj->data->dynSceneArr);
                $dynSceneLenBin = pack("n", $dynSceneLen);
                $reserved10B = 0;
                $reserved10BBin = pack("a10", $reserved10B);
                
                //计算扩展域
                
                $extBin = "";
                //遍历设备
                for ($i = 0; $i < $devArrLen; $i++) {
                    $devCmdObj = $msgJsonObj->data->devArr[$i];
                    $devID = $devCmdObj->devID;
                    $devIDBin = pack("n", $devID);
                    
                    $devSubCmdArr = $devCmdObj->devSubCmdArr;
                    $devSubCmdNum = count($devSubCmdArr);
                    $devSubCmdNumBin = pack("n", $devSubCmdNum);
                    
                    $extBin .= $devIDBin.$devSubCmdNumBin;
                    $extBin .= Third_Ys_Devicesdk::encodeSubCmdArr($devSubCmdArr);
                    
                    $extLen += (2+2+$devSubCmdNum*4);
                }
                
                //遍历静态场景
                for ($i = 0; $i < $staticSceneLen; $i++) {
                    $staticSceneID = $msgJsonObj->data->staticSceneArr[$i];
                    $staticSceneIDBin = pack("n", $staticSceneID);
                    
                    $extBin .= $staticSceneIDBin;
                    
                    $extLen += 2;
                }
                
                //遍历动态场景
                for ($i = 0; $i < $dynSceneLen; $i++) {
                    $dynSceneID = $msgJsonObj->data->dynSceneArr[$i];
                    $dynSceneIDBin = pack("n", $dynSceneID);
                    
                    $extBin .= $dynSceneIDBin;
                    
                    $extLen += 2;
                }
                
                $extLenBin = pack("n", $extLen);
                
                $packBin = $propRegionBin.$objTypeBin.$cmdCodeBin.$objIDBin
                    .$fixLenBin.$extLenBin.$nameUnicodeBin.$phoneBin.$typeBin.$controlFlagBin
                    .$reserved2BBin.$clockFirstByteBin.$hourBin.$minuteBin.$secondBin.$triggerBin.$devArrLenBin.$staticSceneLenBin.$dynSceneLenBin
                    .$reserved10BBin.$extBin;
                break;
            case 3:
                //进入地址围栏
                break;
            case 4:
                //离开地址围栏
                break;
        }
        
        $msgLen = 18+$fixLen + $extLen + 4;
        
        return $packBin;
    }
    
    private static function startTask($msgJsonObj, &$msgLen) {
        echo "\n----------start task encode -----------\n";
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
    
    private static function deleteTask($msgJsonObj, &$msgLen) {
        echo "\n----------delete task encode -----------\n";
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
    
    ///////////////////////// 编码 /////////////////////////////
    
    public static function encodeTaskMsg($msgJsonObj, &$msgLen) {
        
        $cmdCode = $msgJsonObj->cmdCode;
        $propRegion;
        $packBin;
        
        switch ($cmdCode) {
            case self::START_TASKTABLE_CMDCODE:
                //召唤
                $packBin = self::startTask($msgJsonObj, $msgLen);
                break;
            case self::CREATE_TASKTABLE_CMDCODE:
            case self::MODIFY_TASKTABLE_CMDCODE:
                $packBin = self::createTaskTable($msgJsonObj, $msgLen);
                break;
            case self::DELETE_TASKTABLE_CMDCODE:
                //删除任务表
                $packBin = self::deleteTask($msgJsonObj, $msgLen);
                break;
            case self::READ_CONFIG_PROPS_CMDCODE:
                $packBin = Third_Ys_Helpersdk::readConfigEncode($msgJsonObj, $msgLen);
                break;
            case self::LIST_OBJID_CMDCODE:
                $packBin = Third_Ys_Helpersdk::listAllObjIDs($msgJsonObj, $msgLen);
                
                break;
        }
        
        return $packBin;
        
    }
    
    //////////////////// 解码 ////////////////////
    
    public static function decodeTaskMsg($msgBin, &$msgCRC, $typeMap) {
        
        $dataArray;
        $cmdFormat = "@13/C1cmdCode";
        $cmdArr = unpack($cmdFormat, $msgBin);
        $cmdCode = $cmdArr["cmdCode"];
        
        switch ($cmdCode) {
            case self::CREATE_TASKTABLE_CMDCODE:
                $cmdArr = self::createTaskTableDecode($msgBin, $msgCRC);
                break;
            case self::MODIFY_TASKTABLE_CMDCODE:
                $cmdArr = Third_Ys_Helpersdk::decodeCommonErrorMsg($msgBin, $msgCRC);
                break;
            case self::START_TASKTABLE_CMDCODE:
                $cmdArr = Third_Ys_Helpersdk::decodeCommonErrorMsg($msgBin, $msgCRC);
                
                break;
            case self::DELETE_TASKTABLE_CMDCODE:
                $cmdArr = Third_Ys_Helpersdk::decodeCommonErrorMsg($msgBin, $msgCRC);
                break;
            case self::READ_CONFIG_PROPS_CMDCODE:
                $cmdArr = Third_Ys_Helpersdk::readConfigDecode($msgBin, $msgCRC, $typeMap);
                break;
            case self::LIST_OBJID_CMDCODE:
                $cmdArr = Third_Ys_Helpersdk::listAllObjIDsDecode($msgBin, $msgCRC);
                break;
        }
        
        return array('data'=>$cmdArr);
        
    }
    
    private static function createTaskTableDecode($msgBin, &$msgCRC) {
        
        $cmdFormat = "@16/n1cmdRetCode/n1taskID/n1crc";
        $cmdArr = unpack($cmdFormat, $msgBin);
        $msgCRC = $cmdArr["crc"];
        return $cmdArr;
        
    }
    
}
