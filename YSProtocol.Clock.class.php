<?php
//闹钟
class ClockYSProtocol {
    
    const LIST_OBJID_CMDCODE = 195;     //列表同类对象ID
    
    public static function encodeClockMsg($msgJsonObj, &$msgLen) {
        
        $cmdCode = $msgJsonObj->cmdCode;
        $propRegion;
        $packBin;
        
        switch ($cmdCode) {
            case self::LIST_OBJID_CMDCODE:
                $packBin = HelperYSProtocol::listAllObjIDs($msgJsonObj, $msgLen);
                
                break;
        }
        
        return $packBin;
        
    }
    
    //////////////////// 解码 ////////////////////
    
    public static function decodeClockMsg($msgBin, &$msgCRC) {
        
        $dataArray;
        $cmdFormat = "@13/C1cmdCode";
        $cmdArr = unpack($cmdFormat, $msgBin);
        $cmdCode = $cmdArr["cmdCode"];
        
        switch ($cmdCode) {
            case self::LIST_OBJID_CMDCODE:
                $cmdArr = HelperYSProtocol::listAllObjIDsDecode($msgBin, $msgCRC);
                break;
        }
        
        return array('data'=>$cmdArr);
        
    }
    
    
    
}
