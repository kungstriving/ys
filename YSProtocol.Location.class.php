<?php
//地址围栏
class Third_Ys_Locationsdk {
    
    const LIST_OBJID_CMDCODE = 195;     //列表同类对象ID
    
    public static function encodeLocMessage($msgJsonObj, &$msgLen) {
        $cmdCode = $msgJsonObj->cmdCode;
        $propRegion;
        $packBin;
        
        switch ($cmdCode) {
            case self::LIST_OBJID_CMDCODE:
                $packBin = Third_Ys_Helpersdk::listAllObjIDs($msgJsonObj, $msgLen);
                break;
        }
        return $packBin;
    }
    
    //////////////////// 解码 ////////////////////
    
    public static function decodeLocationMsg($msgBin, &$msgCRC) {
        
        $dataArray;
        $cmdFormat = "@13/C1cmdCode";
        $cmdArr = unpack($cmdFormat, $msgBin);
        $cmdCode = $cmdArr["cmdCode"];
        
        switch ($cmdCode) {
            case self::LIST_OBJID_CMDCODE:
                $cmdArr = Third_Ys_Helpersdk::listAllObjIDsDecode($msgBin, $msgCRC);
                break;
        }
        
        return array('data'=>$cmdArr);
        
    }
    
}
