<?php

class Third_Ys_Helpersdk {
    
    public static function decodeObjConfigProps($msgBin, $offset, $objType) {
        
        $props = array();
        
        switch ($objType) {
            case 4:
                //终端站点类型
                $cmdFormat = "@".$offset."/h16devMac/n1parentID/C1devType/C1devNum/H32devName/h2reserved0"
                    ."/C1devSeq/C1protoVer/h8reserved1";
                $cmdArr = unpack($cmdFormat, $msgBin);
                
                $props["devMac"] = $cmdArr["devMac"];
                $props["parentID"] = $cmdArr["parentID"];
                $devType = $cmdArr["devType"];
                $props["devType"] = $devType;
                $props["devNum"] = $cmdArr["devNum"];
                $tempName = $cmdArr["devName"];
                $tempName = Third_Ys_Helpersdk::decodeUnicodeStr($tempName);
                $props["devName"] = $tempName;
                $props["devSeq"] = $cmdArr["devSeq"];
                $props["protoVer"] = $cmdArr["protoVer"];
                
                //扩展域
                $offset = $offset + 36;
                $cmdFormat = "@".$offset."/n1devSignGroup/n1subCmdNum";
                
                $cmdArr = unpack($cmdFormat, $msgBin);
                $props["devSignGroup"] = $cmdArr["devSignGroup"];
                $subCmdNum = $cmdArr["subCmdNum"];
                $props["subCmdNum"] = $subCmdNum;
                $offset = $offset + 4;
                $subCmdArr = array();
                //终端类型不同 处理不同
                for ($i = 0; $i < $subCmdNum; $i++) {
                    $devSubCmdObj = array();
                    switch ($devType) {
                        case 16:
                        case 17:
                            //灯
                            $cmdFormat = "@".$offset."/CdevSeqSubCmd/CcolorH/CcolorS/CcolorB";
                            $cmdArrTemp = unpack($cmdFormat, $msgBin);
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
                        case 40:
                        case 41:
                        case 72:
                        case 80:
                            //开关
                            $cmdFormat = "@".$offset."/CdevSeqSubCmd/CcontrolB1/CcontrolB2/CcontrolB3";
                            $cmdArrTemp = unpack($cmdFormat, $msgBin);
                            $devSeqSubCmd = $cmdArrTemp["devSeqSubCmd"];
                            $subDevNum = $devSeqSubCmd >> 4;    //子设备号 0=所有灯 1-3=1-3灯
                            $subCmd = $devSeqSubCmd & 0x0f;     //0=关闭 1=开启
                            
                            $devSubCmdObj["subDevNum"] = $subDevNum;
                            $devSubCmdObj["powerOn"] = $subCmd;
                            break;
                        case 94:
                            //空调遥控器
                            break;
                        case 64:
                            //窗帘控制器
                            break;
                        case 56:
                            //窗帘控制帖
                            break;
                        case 48:
                            //灯遥控器
                        case 52:
                            //开关贴
                            $cmdFormat = "@".$offset."/CdevSeqSubCmd/CcontrolB1/n1targetObjID";
                            $cmdArrTemp = unpack($cmdFormat, $msgBin);
                            $devSeqSubCmd = $cmdArrTemp["devSeqSubCmd"];
                            $subDevNum = $devSeqSubCmd >> 4;    //按钮1-6
                            $binding = $devSeqSubCmd & 0x0f;     //2=解绑定 3=绑定
                            if ($binding == 3) {
                                $binding = 1;
                            } else {
                                $binding = 0;
                            }
                            $controlB1 = $cmdArrTemp["controlB1"];
                            $targetDevSubNum = $controlB1 >> 4;
                            $targetDevType = $controlB1 & 0x0f;
                            
                            $devSubCmdObj["subDevNum"] = $subDevNum;
                            $devSubCmdObj["binding"] = $binding;
                            $devSubCmdObj["targetDevSubNum"] = $targetDevSubNum;
                            $devSubCmdObj["targetDevType"] = $targetDevType;
                            $devSubCmdObj["targetObjID"] = $cmdArrTemp["targetObjID"];
                            break;
                        case 54:
                            //情景开关贴
                            break;
                        case 96:
                            //门磁监测
                            break;
                        case 97:
                            //红外监测
                            break;
                        case 98:
                            //煤气监测
                            break;
                        case 129:
                            //流明监测
                            break;
                            
                    }
                    
                    $subCmdArr[] = $devSubCmdObj;
                    $offset = $offset + 4;
                }
                
                $props["subCmdArr"] = $subCmdArr;
                break;
        }
        
        return $props;
    }
    
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
