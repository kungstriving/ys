<?php
//静态场景
class Third_Ys_Staticscenesdk {
    
    const CREATE_CMDCODE = 1;
    const DELETE_CMDCODE = 2;
    const MODIFY_CMDCODE = 3;
    const UPDATE_CMDCODE = 4;
    const START_CMDCODE = 16;
    const START_TEMP_CMDCODE = 32;
    const START_MUSIC_CMDCODE = 34;
    const READ_CONFIG_CMDCODE = 192;
    
    const LIST_OBJID_CMDCODE = 195;     //列表同类对象ID
    
    private static function createScene($msgJsonObj, &$msgLen) {
        echo "\n --- create scene ------- \n";
        
        $cmdCode = $msgJsonObj->cmdCode;
        $objType = $msgJsonObj->objType;
        
        $devArr = $msgJsonObj->data->devArr;
        $devNum = count($devArr);
        $devNumBin = pack("n", $devNum);
        
        $propRegion = 0x8000;
        $propRegionBin = pack("n", $propRegion);
        $objTypeBin = pack("C", $objType);
        $cmdCodeBin = pack("C", $cmdCode);
        $objIDBin = pack("n",0x00);
        $fixLen = 28;
        $fixLenBin = pack("n",$fixLen);
        
        $extLen = 0;

        $name = $msgJsonObj->data->name;
        $nameUnicode = Third_Ys_Helpersdk::unicode_encode($name);
        $nameUnicodeBin = pack("a16", $nameUnicode);
        
        $packBin = $propRegionBin.$objTypeBin.$cmdCodeBin.$objIDBin.$fixLenBin;
        
        $packDevBin = "";
        for ($i = 0; $i < $devNum; $i++) {
            $devCmdObj = $devArr[$i];
            $devID = $devCmdObj->devID;
            $devIDBin = pack("n", $devID);
            
            $devSubCmdArr = $devCmdObj->devSubCmdArr;
            $devSubCmdArrLen = count($devSubCmdArr);
            $devSubCmdArrLenBin = pack("n", $devSubCmdArrLen);
            
            $packDevBin .= $devIDBin.$devSubCmdArrLenBin;
            $packTemp = Third_Ys_Devicesdk::encodeSubCmdArr($devSubCmdArr);
            $packDevBin .= $packTemp;
            $extLen += 4+4*$devSubCmdArrLen;
        }
        
        $extLenBin = pack("n", $extLen);
        
        $reserved4B = pack("a4", 0);
        $reserved6B = pack("a6", 0);
        $packBin .= $extLenBin.$nameUnicodeBin.$reserved4B.$devNumBin.$reserved6B.$packDevBin;
        
        $msgLen = 18 + 4 + $fixLen + $extLen;
        
        return $packBin;
    }
    
    private static function startStaticScene($msgJsonObj, &$msgLen) {
        echo "\n----------start scene encode -----------\n";
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
    
    private static function deleteStaticScene($msgJsonObj, &$msgLen) {
        echo "\n----------delete scene encode -----------\n";
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
    
    public static function encodeStaticSceneMsg($msgJsonObj, &$msgLen) {
        
        $cmdCode = $msgJsonObj->cmdCode;
        $propRegion;
        $packBin;
        
        switch ($cmdCode) {
            case self::START_CMDCODE:
                //召唤
                $packBin = self::startStaticScene($msgJsonObj, $msgLen);
                break;
            case self::CREATE_CMDCODE:
                $packBin = self::createScene($msgJsonObj, $msgLen);
                break;
            case self::DELETE_CMDCODE:
                $packBin = self::deleteStaticScene($msgJsonObj, $msgLen);
                break;
            case self::LIST_OBJID_CMDCODE:
                $packBin = Third_Ys_Helpersdk::listAllObjIDs($msgJsonObj, $msgLen);
                
                break;
        }
        
        return $packBin;
        
    }
    
    //////////////////// 解码 ////////////////////
    
    public static function decodeStaticSceneMsg($msgBin, &$msgCRC) {
        
        $dataArray;
        $cmdFormat = "@13/C1cmdCode";
        $cmdArr = unpack($cmdFormat, $msgBin);
        $cmdCode = $cmdArr["cmdCode"];
        
        switch ($cmdCode) {
            case self::CREATE_CMDCODE:
                $cmdArr = self::createSceneDecode($msgBin, $msgCRC);
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
    
    private static function createSceneDecode($msgBin, &$msgCRC) {
        
        echo "\n --- create scene decode ------- \n";
        
        $cmdFormat = "@16/n1cmdRetCode/n1sceneID/n1crc";
        $cmdArr = unpack($cmdFormat, $msgBin);
        $msgCRC = $cmdArr["crc"];
        return $cmdArr;
        
    }
    
}
