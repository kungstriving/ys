<?php 

class ConfigYSProtocol {
    //
    //命令码
    const CONFIG_CMDCODE = 192;   //0xC0
    
    ///////////////////////// 编码 ///////////////////////////
    
    public static function encodeConfigMsg($msgJsonObj, &$msgLen) {
        
        $cmdCode = $msgJsonObj->cmdCode;
        $objType = $msgJsonObj->objType;
        $objID = $msgJsonObj->objID;
        
        $propRegion;
        $packBin;
        
        if ($cmdCode == ConfigYSProtocol::CONFIG_CMDCODE) {
            echo "\n --- read config ---\n";
            $propRegion = 0x8000;
            $propRegionBin = pack("n", $propRegion);
            $objTypeBin = pack("C", $objType);
            $cmdCodeBin = pack("C", $cmdCode);
            $objIDBin = pack("n", $objID);
            $packBin = $propRegionBin.$objTypeBin.$cmdCodeBin
                .$objIDBin;
            $msgLen = 18+0;
            
            return $packBin;
        } else {
            echo "\n --- wrong command code ---\n";
            return "";
        }
        
    }
    
}
