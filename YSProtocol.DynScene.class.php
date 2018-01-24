<?php
//动态场景

class Third_Ys_Dynscenesdk {
    const CREATE_CMDCODE = 1;
    const DELETE_CMDCODE = 2;
    const MODIFY_CMDCODE = 3;
    const UPDATE_CMDCODE = 4;
    const START_CMDCODE = 16;
    const STOP_CMDCODE = 17;
    const START_TEMP_CMDCODE = 32;
    const STOP_TEMP_CMDCODE = 33;
    const START_MUSIC_CMDCODE = 34;
    const STOP_MUSIC_CMDCODE = 35;
    
    const READ_CONFIG_CMDCODE = 192;
    const LIST_OBJID_CMDCODE = 195;     //列表同类对象ID
    
    
    private static function createDynScene($msgJsonObj, &$msgLen) {
        echo "\n --- create dynamic scene ------- \n";
        
        $cmdCode = $msgJsonObj->cmdCode;
        $objType = $msgJsonObj->objType;
        
        $name = $msgJsonObj->data->name;
        $nameUnicode = Third_Ys_Helpersdk::unicode_encode($name);
        $nameUnicodeBin = pack("a16", $nameUnicode);
        
        $template = $msgJsonObj->data->template;
        $templateBin = pack("C", $template);
        $reserved1B = pack("C",0);
        $reserved2B = pack("n", 0);
        
        $powerOn = $msgJsonObj->data->powerOn;
        $powerOnBin = pack("C", $powerOn);
        $colorH = $msgJsonObj->data->colorH;
        $colorHBin = pack("C", $colorH);
        $colorS = $msgJsonObj->data->colorS;
        $colorSBin = pack("C", $colorS);
        $colorB = $msgJsonObj->data->colorB;
        $colorBBin = pack("C", $colorB);
        
        $interval = $msgJsonObj->data->interval;    //秒
        $interval = 10*$interval;
        $intervalBin = pack("n", $interval);
        
        $last = $msgJsonObj->data->last;
        if ($last != 65535) {
            $last = 10*$last;
        }
        $lastBin = pack("n", $last);
        
        $devArr = $msgJsonObj->data->devArr;
        
        $devNum = count($devArr);
        $devNumBin = pack("n", $devNum);
        
        $propRegion = 0x8000;
        $propRegionBin = pack("n", $propRegion);
        $objTypeBin = pack("C", $objType);
        $cmdCodeBin = pack("C", $cmdCode);
        $objIDBin = pack("n",0x00);
        $fixLen = 40;
        $fixLenBin = pack("n",$fixLen);
        
        $extLen = 2*$devNum;
        $extLenBin = pack("n", $extLen);
        
        $packBin = $propRegionBin.$objTypeBin.$cmdCodeBin.$objIDBin.$fixLenBin
            .$extLenBin.$nameUnicodeBin.$templateBin.$reserved1B.$reserved2B
            .$devNumBin.$reserved2B.$powerOnBin.$colorHBin.$colorSBin.$colorBBin
            .$intervalBin.$reserved2B.$lastBin.$reserved2B.$reserved2B.$reserved2B;
        
        for ($i = 0; $i < $devNum; $i++) {
            $devID = $devArr[$i];
            $devIDBin = pack("n", $devID);
            
            $packBin .= $devIDBin;
        }
        
        $msgLen = 18 + 4 + $fixLen + $extLen;
        
        return $packBin;
    }
    
    private static function startDynScene($msgJsonObj, &$msgLen) {
        echo "\n----------start dynamic scene encode -----------\n";
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
    
    private static function deleteDynScene($msgJsonObj, &$msgLen) {
        echo "\n----------delete dynamic scene encode -----------\n";
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
    
    public static function encodeDynSceneMsg($msgJsonObj, &$msgLen) {
        
        $cmdCode = $msgJsonObj->cmdCode;
        $propRegion;
        $packBin;
        
        switch ($cmdCode) {
            case self::START_CMDCODE:
                $packBin = self::startDynScene($msgJsonObj, $msgLen);
                break;
            case self::CREATE_CMDCODE:
                $packBin = self::createDynScene($msgJsonObj, $msgLen);
                break;
            case self::DELETE_CMDCODE:
                $packBin = self::deleteDynScene($msgJsonObj, $msgLen);
                break;
            case self::LIST_OBJID_CMDCODE:
                $packBin = Third_Ys_Helpersdk::listAllObjIDs($msgJsonObj, $msgLen);
                
                break;
        }
        
        return $packBin;
        
    }
    
    //////////////////// 解码 ////////////////////
    
    public static function decodeDynSceneMsg($msgBin, &$msgCRC) {
        
        $dataArray;
        $cmdFormat = "@13/C1cmdCode";
        $cmdArr = unpack($cmdFormat, $msgBin);
        $cmdCode = $cmdArr["cmdCode"];
        
        switch ($cmdCode) {
            case self::CREATE_CMDCODE:
                $cmdArr = self::createDynSceneDecode($msgBin, $msgCRC);
                break;
            case self::START_CMDCODE:
                $cmdArr = Third_Ys_Helpersdk::decodeCommonErrorMsg($msgBin, $msgCRC);
                break;
            case self::DELETE_CMDCODE:
                $cmdArr = Third_Ys_Helpersdk::decodeCommonErrorMsg($msgBin, $msgCRC);
                break;
            case self::LIST_OBJID_CMDCODE:
                $cmdArr = Third_Ys_Helpersdk::listAllObjIDsDecode($msgBin, $msgCRC);
                break;
        }
        
        return array('data'=>$cmdArr);
    }
    
    private static function createDynSceneDecode($msgBin, &$msgCRC) {
        
        echo "\n --- create dynamic scene decode ------- \n";
        
        $cmdFormat = "@16/n1cmdRetCode/n1sceneID/n1crc";
        $cmdArr = unpack($cmdFormat, $msgBin);
        $msgCRC = $cmdArr["crc"];
        return $cmdArr;
        
    }
}
