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
            case Third_Ys_Sdk::GATE_OBJ_TYPE:
                $dataBin = GateYSProtocol::encodeGateMsg($msgJsonObj, $msgLen);
                break;
            case Third_Ys_Sdk::DEVICE_TYPE:
                $dataBin = DeviceYSProtocol::encodeDeviceMsg($msgJsonObj, $msgLen);
                break;
            case Third_Ys_Sdk::GROUP_TYPE:
                //分组
                $dataBin = GroupYSProtocol::encodeGroupMsg($msgJsonObj, $msgLen);
                break;
            case Third_Ys_Sdk::STATIC_SCENE_TYPE:
                //静态场景
                $dataBin = StaticSceneYSProtocol::encodeStaticSceneMsg($msgJsonObj, $msgLen);
                break;
            case Third_Ys_Sdk::DYNAMIC_SCENE_TYPE:
                //动态场景
                $dataBin = DynSceneYSProtocol::encodeDynSceneMsg($msgJsonObj, $msgLen);
                break;
            case Third_Ys_Sdk::CLOCK_TYPE:
                //闹钟
                $dataBin = ClockYSProtocol::encodeClockMsg($msgJsonObj, $msgLen);
                break;
            case Third_Ys_Sdk::LOCATION_TYPE:
                //地址围栏
                $dataBin = LocationYSProtocol::encodeLocationMsg($msgJsonObj, $msgLen);
                break;
            case Third_Ys_Sdk::TASKTABLE_TYPE:
                //任务表
                $dataBin = TaskTableYSProtocol::encodeTaskMsg($msgJsonObj, $msgLen);
                break;
            case Third_Ys_Sdk::ALL_TYPE:
                //所有类型
                $dataBin = DeviceYSProtocol::encodeDeviceMsg($msgJsonObj, $msgLen);
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
            case Third_Ys_Sdk::GATE_OBJ_TYPE:
                
                $dataArray = GateYSProtocol::decodeGateMsg($msgBinReal, $msgCRC);
                break;
            case Third_Ys_Sdk::DEVICE_TYPE:
                $dataArray = DeviceYSProtocol::decodeDeviceMsg($msgBinReal, $msgCRC);
                break;
            case Third_Ys_Sdk::GROUP_TYPE:
                $dataArray = GroupYSProtocol::decodeGroupMsg($msgBinReal, $msgCRC);
                break;
            case Third_Ys_Sdk::STATIC_SCENE_TYPE:
                $dataArray = StaticSceneYSProtocol::decodeStaticSceneMsg($msgBinReal, $msgCRC);
                break;
            case Third_Ys_Sdk::DYNAMIC_SCENE_TYPE:
                $dataArray = DynSceneYSProtocol::decodeDynSceneMsg($msgBinReal, $msgCRC);
                break;
            case Third_Ys_Sdk::CLOCK_TYPE:
                $dataArray = ClockYSProtocol::decodeClockMsg($msgBinReal, $msgCRC);
                break;
            case Third_Ys_Sdk::LOCATION_TYPE:
                $dataArray = LocationYSProtocol::decodeLocationMsg($msgBinReal, $msgCRC);
                break;
            case Third_Ys_Sdk::TASKTABLE_TYPE:
                $dataArray = TaskTableYSProtocol::decodeTaskMsg($msgBinReal, $msgCRC);
                break;
            case Third_Ys_Sdk::ALL_TYPE:
                $dataArray = DeviceYSProtocol::decodeDeviceMsg($msgBinReal, $msgCRC);
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


