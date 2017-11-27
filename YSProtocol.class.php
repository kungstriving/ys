<?php

include 'YSProtocol.class.php';
//include 'YSProtocol.class.min.php';

///////////////// 测试 /////////////////////

//////////////// 手机局域网登录 /////////////////
$testLoginLanJson = '{
    "sign":1234,
    "msgID":101,
    "objType":0,
    "cmdCode":1,
    "objID":0,
    "data":{
        "username":"test",
        "phoneNum":"13366666666",
        "gateID":"987654321"
    },
    "crc":555
}';

//objType = 0 //关
//cmdCode = 1 //手机局域网登录

$testBin = YSProtocol::encodeMsg($testLoginLanJson);

//没有真实数据
//$decodedJson = YSProtocol::decodeMsg($testBin);

//echo json_decode($decodedJson)->data->username;

//////////////// 手机服务器登录 //////////////////

// objType = 0; //网关
//cmdCode = 2;//手机服务器登录
$testLoginServerJson = '{
    "sign":1234,
    "msgID":101,
    "objType":0,
    "cmdCode":2,
    "objID":0,
    "data":{
        "username":"test",
        "phoneNum":"13366666666",
        "gateID":"987654321"
    },
    "crc":555
}';

$testBin = YSProtocol::encodeMsg($testLoginServerJson);


///////////////// 手机退出登录 ////////////////
$testLogoutJson = '{
    "sign":1234,
    "msgID":102,
    "objType":0,
    "cmdCode":3,
    "objID":0
}';

$testBin = YSProtocol::encodeMsg($testLogoutJson);


//////////////// 手机心跳 ////////////////////
$testHeartBeatJson = '{
    "sign":1234,
    "msgID":103,
    "objType":0,
    "cmdCode":4,
    "objID":0,
    "data":{
        "phoneNum":"13366666666",
        "year":2017,
        "month":11,
        "day":25,
        "hour":10,
        "minute":39,
        "second":10,
        "weekday":4,
        "hb":300
    },
    "crc":255
}';

$testBin = YSProtocol::encodeMsg($testHeartBeatJson);

////////////////// 服务器识别网关 ///////////
$testServerIdentifyGateJson = '{
    "sign":1234,
    "msgID":104,
    "objType":0,
    "cmdCode":5,
    "objID":0,
    "data":{
        "serverID":"YS-PHP-Server"
    },
    "crc":255
}';

$testBin = YSProtocol::encodeMsg($testServerIdentifyGateJson);
