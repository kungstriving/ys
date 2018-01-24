<?php
include 'YSProtocol.Helper.class.php';
// $testConfigReadAllDev = '{
//     "sign":50210,
//     "msgID":108,
//     "objType":4,
//     "cmdCode":192,
//     "objID":65535,
//     "crc":255
// }';

// $msgJsonObj = json_decode($testConfigReadAllDev);


// if (property_exists($msgJsonObj, "sliceM")) {
//     echo "OK\n";
// } else {
//     echo "hahah\n";
// }

// // echo pack('H*',"621176845bb6");

// // echo chr(hexdec("621176845bb6"));

// function unicode_decode($name){
    
//     $json = '{"str":"'.$name.'"}';
//     $arr = json_decode($json,true);
//     if(empty($arr)) return '';
//     return $arr['str'];
// }

// $testStr = "621176845bb600000000000000000000";
// $testStr = chunk_split($testStr,4,"\u");
// $testStr = "\u".$testStr;
// $testStr = substr($testStr, 0, (strlen($testStr) - 2));

// echo $testStr . "\n";
// $aihao = unicode_decode($testStr);
// echo $aihao;

$teststr = "孔晓阳";
// echo Third_Ys_Helpersdk::utf8_str_to_unicode($teststr);
echo Third_Ys_Helpersdk::unicode_encode($teststr);

echo "\n";

$unistr = "5b5466539633";
//"3664346238626435"
echo Third_Ys_Helpersdk::decodeUnicodeStr($unistr);

echo "\n";

$objType = 4;
$objTypeBin = pack("C", $objType);

$phone = "13366666666";
$phone = Third_Ys_Helpersdk::ascEncode($phone);
$phoneBin = pack("a16", $phone);

echo $phoneBin;

$testHex = "00d0f60020bc1355";
echo "\n".pack("H16", $testHex)."\n";
echo "\n".bin2hex(pack("H16", $testHex))."\n";

$arrtemp = unpack("H32", $testHex);

var_dump($arrtemp);

function foo($a,$b=array()) {
    var_dump($a);
    var_dump($b);
    echo $b[200];
}

$testMap = array();
$testMap[200] = 96;
$testMap[300] = 98;
foo(4,$testMap);

echo "\n----------".pack("a4", 0);


echo "\n----------".pack("C", 0);

