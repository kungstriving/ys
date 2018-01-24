<?php

include 'YSProtocol.Gate.class.php';
include 'YSProtocol.Device.class.php';
include 'YSProtocol.Group.class.php';
include 'YSProtocol.StaticScene.class.php';
include 'YSProtocol.DynScene.class.php';
include 'YSProtocol.Clock.class.php';
include 'YSProtocol.Location.class.php';
include 'YSProtocol.TaskTable.class.php';
include 'YSProtocol.Helper.class.php';

// include 'YSProtocol.Gate.class.min.php';
// include 'YSProtocol.Device.class.min.php';
// include 'YSProtocol.Group.class.min.php';
// include 'YSProtocol.StaticScene.class.min.php';
// include 'YSProtocol.DynScene.class.min.php';
// include 'YSProtocol.Clock.class.min.php';
// include 'YSProtocol.Location.class.min.php';
// include 'YSProtocol.TaskTable.class.min.php';
// include 'YSProtocol.Helper.class.min.php';


class Third_Ys_Sdk {
    //对象类型
    const GATE_OBJ_TYPE = 0;
    const DEVICE_TYPE = 4;
    const GROUP_TYPE = 5;
    const STATIC_SCENE_TYPE = 7;
    const DYNAMIC_SCENE_TYPE = 8;
    const CLOCK_TYPE = 9;
    const LOCATION_TYPE = 10;
    const TASKTABLE_TYPE = 11;
    
    const ALL_TYPE = 255;
    const LIGHT_OBJ_TYPE = 16;
    const LIGHT_BELT_OBJ_TYPE = 17;
    
    /**
     * 根据json消息返回二进制数据
     * 
     * @param $msgJson
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
            case Third_Ys_Sdk::GATE_OBJ_TYPE:
                $dataBin = Third_Ys_Gatesdk::encodeGateMsg($msgJsonObj, $msgLen);
                break;
            case Third_Ys_Sdk::DEVICE_TYPE:
                $dataBin = Third_Ys_Devicesdk::encodeDeviceMsg($msgJsonObj, $msgLen);
                break;
            case Third_Ys_Sdk::GROUP_TYPE:
                //分组
                $dataBin = Third_Ys_Groupsdk::encodeGroupMsg($msgJsonObj, $msgLen);
                break;
            case Third_Ys_Sdk::STATIC_SCENE_TYPE:
                //静态场景
                $dataBin = Third_Ys_Staticscenesdk::encodeStaticSceneMsg($msgJsonObj, $msgLen);
                break;
            case Third_Ys_Sdk::DYNAMIC_SCENE_TYPE:
                //动态场景
                $dataBin = Third_Ys_Dynscenesdk::encodeDynSceneMsg($msgJsonObj, $msgLen);
                break;
            case Third_Ys_Sdk::CLOCK_TYPE:
                //闹钟
                $dataBin = Third_Ys_Clocksdk::encodeClockMsg($msgJsonObj, $msgLen);
                break;
            case Third_Ys_Sdk::LOCATION_TYPE:
                //地址围栏
                $dataBin = Third_Ys_Locationsdk::encodeLocMessage($msgJsonObj, $msgLen);
                break;
            case Third_Ys_Sdk::TASKTABLE_TYPE:
                //任务表
                $dataBin = Third_Ys_Tasktablesdk::encodeTaskMsg($msgJsonObj, $msgLen);
                break;
            case Third_Ys_Sdk::ALL_TYPE:
                //所有类型
                //所有类型的读取应该放在网关类，或者CONFIG类中完成
                $dataBin = Third_Ys_Devicesdk::encodeDeviceMsg($msgJsonObj, $msgLen);
                break;
            default:
                
                break;
        }
        
        $msgLenBin = pack("n", $msgLen);
        
        $msgCrc = 255;      //暂固定
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
    public static function decodeMsg($msgBin, $typeMap=array()) {
        echo "[YSProtocol::decodeMsg] for -- ".$msgBin."\n\n";
        
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
            case Third_Ys_Sdk::GATE_OBJ_TYPE:
                
                $dataArray = Third_Ys_Gatesdk::decodeGateMsg($msgBinReal, $msgCRC);
                break;
            case Third_Ys_Sdk::DEVICE_TYPE:
                $dataArray = Third_Ys_Devicesdk::decodeDeviceMsg($msgBinReal, $msgCRC);
                break;
            case Third_Ys_Sdk::GROUP_TYPE:
                $dataArray = Third_Ys_Groupsdk::decodeGroupMsg($msgBinReal, $msgCRC);
                break;
            case Third_Ys_Sdk::STATIC_SCENE_TYPE:
                $dataArray = Third_Ys_Staticscenesdk::decodeStaticSceneMsg($msgBinReal, $msgCRC);
                break;
            case Third_Ys_Sdk::DYNAMIC_SCENE_TYPE:
                $dataArray = Third_Ys_Dynscenesdk::decodeDynSceneMsg($msgBinReal, $msgCRC);
                break;
            case Third_Ys_Sdk::CLOCK_TYPE:
                $dataArray = Third_Ys_Clocksdk::decodeClockMsg($msgBinReal, $msgCRC);
                break;
            case Third_Ys_Sdk::LOCATION_TYPE:
                $dataArray = Third_Ys_Locationsdk::decodeLocationMsg($msgBinReal, $msgCRC);
                break;
            case Third_Ys_Sdk::TASKTABLE_TYPE:
                $dataArray = Third_Ys_Tasktablesdk::decodeTaskMsg($msgBinReal, $msgCRC, $typeMap);
                break;
            case Third_Ys_Sdk::ALL_TYPE:
                $dataArray = Third_Ys_Configsdk::decodeConfigMsg($msgBinReal, $msgCRC, $typeMap);
                break;
        }
        
        //加入CRC
        $commonArray["crc"] = $msgCRC;
        
        $msgArray = array_merge($commonArray, $dataArray);
        var_dump($msgArray);
        
        $msgJson = json_encode($msgArray, JSON_UNESCAPED_UNICODE);
        
        echo "[YSProtocol::decodeMsg] result in json is " . $msgJson . "\n\n";
        return $msgJson;
    }
    
}


