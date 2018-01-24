<?php

class Third_Ys_Helpersdk {
    
    public static function readConfigDecode($msgBin, &$msgCRC, $typeMap) {
        echo "\n --- read config decode ---\n";
        //读取设备配置信息
        
        //根据单个或同类所有设备来区分
        $cmdFormat = "@14/n1objID";
        $cmdArr = unpack($cmdFormat, $msgBin);
        $objID = $cmdArr["objID"];
        
        if ($objID == 65535) {
            //65535=同类别所有对象
            
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
                        $dataObj["objContent"] = Third_Ys_Helpersdk::decodeObjConfigProps($msgBin, $offset, $dataObjType, $typeMap);
                        
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
    
    public static function decodeCommonErrorMsg($msgBin, &$msgCRC) {
        $cmdFormat = "@16/n1cmdRetCode/n1crc";
        $cmdArr = unpack($cmdFormat, $msgBin);
        $msgCRC = $cmdArr["crc"];
        return $cmdArr;
    }
    
    public static function decodeObjConfigProps($msgBin, $offset, $objType, $typeMap) {
        
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
                    
                    $subCmdArr[] = Third_Ys_Devicesdk::decodeSubCmdBin($msgBin, $offset, $devType);
                    $offset = $offset + 4;
                }
                
                $props["subCmdArr"] = $subCmdArr;
                break;
                
            case 11:
                //任务表
                $cmdFormat = "@".$offset."/A16name/A16phone/C1taskType/C1controlFlag/n1reserved";
                $cmdArr = unpack($cmdFormat, $msgBin);
                
                $taskName = $cmdArr["name"];
                $phone = $cmdArr["phone"];
                $taskType = $cmdArr["taskType"];
                $controlFlag = $cmdArr["controlFlag"];
                
                $props["name"] = Third_Ys_Helpersdk::decodeUnicodeStr($taskName);
                $props["phone"] = $phone;
                $props["type"] = $taskType;
                $props["controlFlag"] = $controlFlag;
                
                $offset = $offset + 36;
                //根据任务类别不同触发条件不同
                if ($taskType == 1) {
                    //情景模式
                    
                } else if ($taskType = 2) {
                    //闹钟
                    $cmdFormat = "@".$offset."/C1clockFirstByte/C1hour/C1minute/C1second";
                    $cmdArr = unpack($cmdFormat, $msgBin);
                    
                    $clockSet = array();
                    
                    $clockFirstByte = $cmdArr["clockFirstByte"];
                    $hour = $cmdArr["hour"];
                    $minute = $cmdArr["minute"];
                    $second = $cmdArr["second"];
                    
                    $repeat = $clockFirstByte & 0x80;
                    $day1 = $clockFirstByte & 0x01;
                    $day2 = $clockFirstByte & 0x02;
                    $day3 = $clockFirstByte & 0x04;
                    $day4 = $clockFirstByte & 0x08;
                    $day5 = $clockFirstByte & 0x10;
                    $day6 = $clockFirstByte & 0x20;
                    $day7 = $clockFirstByte & 0x40;
                    
                    $clockSet["repeat"] = $repeat;
                    
                    $clockSet["day1"] = $day1;
                    $clockSet["day2"] = $day2;
                    $clockSet["day3"] = $day3;
                    $clockSet["day4"] = $day4;
                    $clockSet["day5"] = $day5;
                    $clockSet["day6"] = $day6;
                    $clockSet["day7"] = $day7;
                    
                    $clockSet["hour"] = $hour;
                    $clockSet["minute"] = $minute;
                    $clockSet["second"] = $second;
                    
                    $props["clockSet"] = $clockSet;
                } else if ($taskType = 3){
                    //进入地址围栏
                } else {
                    //离开地址围栏
                }
                
                $offset = $offset + 92;
                
                $cmdFormat = "@".$offset."/n1devNum/n1staticNum/n1dynNum";
                $cmdArr = unpack($cmdFormat, $msgBin);
                
                $devNum = $cmdArr["devNum"];
                $staticNum = $cmdArr["staticNum"];
                $dynNum = $cmdArr["dynNum"];
                
                $offset = $offset + 16;
                
                
                /////// 扩展域 ///////////
                
                $devCmdArr = array();
                
                //循环读取站点信息
                for($i = 0; $i < $devNum; $i++) {
                    $devCmdObj = array();
                    //读取站点类型
                    $cmdFormatTemp = "@".$offset."/n1devID/n1devSubCmdNum";
                    $cmdArrTemp = unpack($cmdFormatTemp, $msgBin);
                    $devCmdObj["devID"] = $cmdArrTemp["devID"];
                    $devIDTemp = $cmdArrTemp["devID"];
                    
                    $devType = $typeMap[$devIDTemp];    //从typeMap中获取设备类型
                    $devCmdObj["devType"] = $devType;
                    $devSubCmdNum = $cmdArrTemp["devSubCmdNum"];
                    $devCmdObj["devSubCmdNum"] = $devSubCmdNum;
                    $devSubCmdArr = array();
                    $offset = $offset + 4;
                    for($j = 0; $j < $devSubCmdNum; $j++) {
                        $devSubCmdArr[] = Third_Ys_Devicesdk::decodeSubCmdBin($msgBin, $offset, $devType);
                        $offset = $offset + 4;
                    }
                    
                    $devCmdObj["devSubCmdArr"] = $devSubCmdArr;
                    
                    $devCmdArr[] = $devCmdObj;
                }
                
                $props["devArr"] = $devCmdArr;
                
                //静态场景
                $staticSceneArr = array();
                
                for ($i = 0; $i < $staticNum; $i++) {
                    $cmdFormatTemp = "@".$offset."/n1staticSceneID";
                    $cmdArrTemp = unpack($cmdFormatTemp, $msgBin);
                    
                    $staticSceneArr[] = $cmdArrTemp["staticSceneID"];
                }
                
                $props["staticSceneArr"] = $staticSceneArr;
                
                //动态场景
                $dynSceneArr = array();
                
                for ($i = 0; $i < $dynNum; $i++) {
                    $cmdFormatTemp = "@".$offset."/n1dynSceneID";
                    $cmdArrTemp = unpack($cmdFormatTemp, $msgBin);
                    
                    $dynSceneArr[] = $cmdArrTemp["dynSceneID"];
                }
                
                $props["dynSceneArr"] = $dynSceneArr;
                
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
    
    
    public static function readConfigEncode($msgJsonObj, &$msgLen) {
        echo "\n --- read type configs ---\n";
        
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
        
        return $packBin;
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
