<?php
//分组类
class Third_Ys_Groupsdk {

    const CREATE_GROUP_CMDCODE = 1;
    const DELETE_GROUP_CMDCODE = 2;
    const MODIFY_GROUP_CMDCODE = 3;
    const UPDATE_GROUP_CMDCODE = 4;
    const REMOTE_CONTROL_GROUP_CMDCODE = 16;
    const READ_GROUP_CONFIG = 192;
    const LIST_OBJID_CMDCODE = 195;     //列表同类对象ID
    
    private static function createGroupEncode($msgJsonObj, &$msgLen) {
        echo "\n --- create group ------- \n";
        
        $cmdCode = $msgJsonObj->cmdCode;
        $objType = $msgJsonObj->objType;
        
        $groupName = $msgJsonObj->data->name;
        $devArr = $msgJsonObj->data->devArr;
        
        $devNum = count($devArr);
        
        $propRegion = 0x8000;
        $propRegionBin = pack("n", $propRegion);
        $objTypeBin = pack("C", $objType);
        $cmdCodeBin = pack("C", $cmdCode);
        $objIDBin = pack("n",0x00);
        $fixLenBin = pack("n",28);
        $extLenBin = pack("n", 2*$devNum);
        
        $nameUnicode = Third_Ys_Helpersdk::unicode_encode($groupName);
        $nameUnicodeBin = pack("a16", $nameUnicode);
        $reserved4B = pack("a4", 0);
        $devNumBin = pack("n", $devNum);
        $reserved6B = pack("a6", 0);
        
        $packBin = $propRegionBin.$objTypeBin.$cmdCodeBin.$objIDBin.$fixLenBin.$extLenBin
            .$nameUnicodeBin.$reserved4B.$devNumBin.$reserved6B;
        for ($i = 0; $i < $devNum; $i++) {
            $devIDTemp = pack("n", $devArr[$i]);
            $packBin .= $devIDTemp;
        }
        
        $msgLen = 18 + 32 + 2*$devNum;
        
        return $packBin;
    }
    
    private static function deleteGroupEncode($msgJsonObj, &$msgLen) {
        echo "\n --- delete group ------- \n";
        
        $cmdCode = $msgJsonObj->cmdCode;
        $objType = $msgJsonObj->objType;
        
        $propRegion = 0x8000;
        $propRegionBin = pack("n", $propRegion);
        $objTypeBin = pack("C", $objType);
        $cmdCodeBin = pack("C", $cmdCode);
        $objIDBin = pack("n",$msgJsonObj->objID);
        
        $packBin = $propRegionBin.$objTypeBin.$cmdCodeBin.$objIDBin;
        
        $msgLen = 18;
        
        return $packBin;
    }
    
    public static function encodeGroupMsg($msgJsonObj, &$msgLen) {
        
        $cmdCode = $msgJsonObj->cmdCode;
        $propRegion;
        $packBin;
        
        switch ($cmdCode) {
            case self::CREATE_GROUP_CMDCODE:
                $packBin = self::createGroupEncode($msgJsonObj, $msgLen);
                break;
            case self::DELETE_GROUP_CMDCODE:
                $packBin = self::deleteGroupEncode($msgJsonObj, $msgLen);
                break;
            case self::LIST_OBJID_CMDCODE:
                $packBin = Third_Ys_Helpersdk::listAllObjIDs($msgJsonObj, $msgLen);
                
                break;
        }
        
        return $packBin;
        
    }
    
    //////////////////// 解码 ////////////////////
    
    public static function decodeGroupMsg($msgBin, &$msgCRC) {
        
        $dataArray;
        $cmdFormat = "@13/C1cmdCode";
        $cmdArr = unpack($cmdFormat, $msgBin);
        $cmdCode = $cmdArr["cmdCode"];
        
        switch ($cmdCode) {
            case self::CREATE_GROUP_CMDCODE:
                $cmdArr = self::createGroupDecode($msgBin, $msgCRC);
                break;
            case self::DELETE_GROUP_CMDCODE:
                $cmdArr = Third_Ys_Helpersdk::decodeCommonErrorMsg($msgBin, $msgCRC);
                break;
            case self::LIST_OBJID_CMDCODE:
                $cmdArr = Third_Ys_Helpersdk::listAllObjIDsDecode($msgBin, $msgCRC);
                break;
        }
        
        return array('data'=>$cmdArr);
        
    }
    
    private static function createGroupDecode($msgBin, &$msgCRC) {
        
        echo "\n --- create group decode ------- \n";
        
        $cmdFormat = "@16/n1cmdRetCode/n1groupID/n1crc";
        $cmdArr = unpack($cmdFormat, $msgBin);
        $msgCRC = $cmdArr["crc"];
        return $cmdArr;
        
    }
    
}
