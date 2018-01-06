<?php

$testConfigReadAllDev = '{
    "sign":50210,
    "msgID":108,
    "objType":4,
    "cmdCode":192,
    "objID":65535,
    "crc":255
}';

$msgJsonObj = json_decode($testConfigReadAllDev);


if (property_exists($msgJsonObj, "sliceM")) {
    echo "OK\n";
} else {
    echo "hahah\n";
}

// echo pack('H*',"621176845bb6");

// echo chr(hexdec("621176845bb6"));

function unicode_decode($name){
    
    $json = '{"str":"'.$name.'"}';
    $arr = json_decode($json,true);
    if(empty($arr)) return '';
    return $arr['str'];
}

$testStr = "621176845bb600000000000000000000";
$testStr = chunk_split($testStr,4,"\u");
$testStr = "\u".$testStr;
$testStr = substr($testStr, 0, (strlen($testStr) - 2));

echo $testStr . "\n";
$aihao = unicode_decode($testStr);
echo $aihao;


