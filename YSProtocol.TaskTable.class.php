<?php
//任务表
class TaskTableYSProtocol {
    
    const LIST_OBJID_CMDCODE = 195;     //列表同类对象ID
    
    public static function encodeTaskMsg($msgJsonObj, &$msgLen) {
        
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
    
    public static function decodeTaskMsg($msgBin, &$msgCRC) {
        
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
