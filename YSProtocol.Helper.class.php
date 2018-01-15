<?php

class Third_Ys_Helpersdk {
    
    public static function decodeCommonErrorMsg($msgBin, &$msgCRC) {
        $cmdFormat = "@16/n1cmdRetCode/n1crc";
        $cmdArr = unpack($cmdFormat, $msgBin);
        $msgCRC = $cmdArr["crc"];
        return $cmdArr;
    }
    
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
                        case 54:
                            //情景开关贴
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
                        case 96:
                            //门磁监测
                        case 97:
                            //红外监测
                        case 98:
                            //煤气监测
                            $cmdFormat = "@".$offset."/CdevSeqSubCmd/CcontrolB1/CcontrolB2/CcontrolB3";
                            $cmdArrTemp = unpack($cmdFormat, $msgBin);
                            $devSeqSubCmd = $cmdArrTemp["devSeqSubCmd"];
                            $subDevNum = $devSeqSubCmd >> 4;    //子设备号 为1
                            $subCmd = $devSeqSubCmd & 0x0f;     //子命令码 工作模式  1=直接设置 2=解绑定 3=绑定 4=关闭 5=开启
                            
                            switch ($subCmd) {
                                case 1:
                                    //工作模式
                                    $controlB1 = $cmdArrTemp["controlB1"];
                                    $securityReport = $controlB1 & 0x01;
                                    $realReport = $controlB1 & 0x02;
                                    $nightMode = $controlB1 & 0x04;
                                    
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
                            $cmdFormat = "@".$offset."/CdevSeqSubCmd/CcontrolB1/n1controlB23";
                            $cmdArrTemp = unpack($cmdFormat, $msgBin);
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
                            $cmdFormat = "@".$offset."/CdevSeqSubCmd/CcontrolB1/CcontrolB2/CcontrolB3";
                            $cmdArrTemp = unpack($cmdFormat, $msgBin);
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
                    
                    $subCmdArr[] = $devSubCmdObj;
                    $offset = $offset + 4;
                }
                
                $props["subCmdArr"] = $subCmdArr;
                break;
        }
        
        return $props;
    }
    
    public static function ascEncode($str) {
        $len = strlen($str);
        $out = "";
        for ($i = 0; $i < $len; $i++) {
            $c = $str[$i];
            $out .= dechex(ord($c));
        }
        
        return $out;
    }
    
    public static function unicode_encode($name,$in_charset='UTF-8',$out_charset='UCS-2BE')
    {
        $name = iconv($in_charset, $out_charset, $name);
        $len = strlen($name);
        $str = '';
        for ($i = 0; $i < $len - 1; $i = $i + 2){
            $c = $name[$i];
            $c2 = $name[$i + 1];
            if (ord($c) > 0){    // 两个字节的文字
                $str .= base_convert(ord($c), 10, 16).base_convert(ord($c2), 10, 16);
            }
            else{
//                 $str .= iconv('UTF-8', 'UTF-8//IGNORE', $c2);
//                 ord($string)
                $str .= '00'.dechex(ord($c2));
            }
        }
        return $str;
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
