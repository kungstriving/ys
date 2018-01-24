<?php 

class Third_Ys_Configsdk {
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
        
        if ($cmdCode == Third_Ys_Configsdk::CONFIG_CMDCODE) {
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
    
    /**
     * 解码所有配置信息
     * @param unknown $msgBin
     * @param unknown $msgCRC
     * @return array
     */
    public static function decodeConfigMsg($msgBin, &$msgCRC, $typeMap) {
        
        $dataArray;
        $cmdFormat = "@13/C1cmdCode";
        $cmdArr = unpack($cmdFormat, $msgBin);
        $cmdCode = $cmdArr["cmdCode"];
        
        switch ($cmdCode) {
            case self::READ_CONFIG_PROPS_CMDCODE:
                $cmdArr = Third_Ys_Helpersdk::readConfigDecode($msgBin, $msgCRC, $typeMap);
                break;
        }
        
        return array('data'=>$cmdArr);
    }
    
}
