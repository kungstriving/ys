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
        "username":"kxy",
        "phoneNum":"13361815235",
        "gateID":"87654321"
    },
    "crc":254
}';

//objType = 0 //关
//cmdCode = 1 //手机局域网登录

$testBin = YSProtocol::encodeMsg($testLoginLanJson);

//
$testBin = "FEFEFE7E00FA04D2006540000001000000006B7879000000000000000000000000000000000000000000000000000000000031333336313831353233350000000000383736353433323100FF000000000000000000000000000000000000000000000000000000000000FF7E008800000301000D6F0002CB3155ACCF23BFB41A000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000012C012C000D6F0002CB3155FF216154B71C31FA4C09D22390811861A69799BE9B9BBF9494979F929D9BD0C7010100008639021366C54FF74C5D385002800100005000006A950819375F";

$decodedJson = YSProtocol::decodeMsg($testBin);

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

//$testBin = YSProtocol::encodeMsg($testLoginServerJson);


///////////////// 手机退出登录 ////////////////
$testLogoutJson = '{
    "sign":1234,
    "msgID":102,
    "objType":0,
    "cmdCode":3,
    "objID":0
}';

//$testBin = YSProtocol::encodeMsg($testLogoutJson);


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

//$testBin = YSProtocol::encodeMsg($testHeartBeatJson);

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

//$testBin = YSProtocol::encodeMsg($testServerIdentifyGateJson);

/////////// 搜索新终端 ////////
$testSearchNewDevsJson = '{
    "sign":255,
    "msgID":105,
    "objType":0,
    "cmdCode":10,
    "objID":0,
    "data":{
        "lastSecs":10
    },
    "crc":111
}';

//$testBin = YSProtocol::encodeMsg($testSearchNewDevsJson);

$testBin = "FEFEFE7E001600FF00694000000B0000000000002042";

//$decodedJson = YSProtocol::decodeMsg($testBin);

/////////// 列表网关所有终端 ////////
$testListAllDevsJson = '{
    "sign":255,
    "msgID":105,
    "objType":0,
    "cmdCode":11,
    "objID":0,
    "crc":255
}';

$testBin = YSProtocol::encodeMsg($testListAllDevsJson);

$testBin = "FEFEFE7E003A00FF00694000000B000000000003DF9C1001000D6F000D317F5D0B2D1001000D6F000D314DFE8DA21001000D6F000D315F51683E";

$decodedJson = YSProtocol::decodeMsg($testBin);
