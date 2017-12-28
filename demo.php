<?php

include 'YSProtocol.class.php';
// include 'YSProtocol.class.min.php';

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

//$testBin = YSProtocol::encodeMsg($testLoginLanJson);

//
$testBin = "FEFEFE7E00FA04D2006540000001000000006B7879000000000000000000000000000000000000000000000000000000000031333336313831353233350000000000383736353433323100FF000000000000000000000000000000000000000000000000000000000000FF7E008800000301000D6F0002CB3155ACCF23BFB41A000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000012C012C000D6F0002CB3155FF216154B71C31FA4C09D22390811861A69799BE9B9BBF9494979F929D9BD0C7010100008639021366C54FF74C5D385002800100005000006A950819375F";

//$decodedJson = Third_Ys_Sdk::decodeMsg($testBin);

//echo json_decode($decodedJson)->data->username;

//////////////// 手机服务器登录 //////////////////
///////////// [测试通过] ///// 
// objType = 0; //网关
//cmdCode = 2;//手机服务器登录
$testLoginServerJson = '{
    "sign":0,
    "msgID":101,
    "objType":0,
    "cmdCode":2,
    "objID":0,
    "data":{
        "username":"test",
        "phoneNum":"13366666666",
        "gateID":"00d0f60020bc1355"
    },
    "crc":555
}';

//fefefe7e006c0000006580000002000000007465737400000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000031333336363636363636360000000000000d6f0002cb315500ff

$testBin = Third_Ys_Sdk::encodeMsg($testLoginServerJson);

$testBin = "FEFEFE7E00FA0000006540000002000000007465737400000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000031333336363636363636360000000000000D6F0002CB3155131C008800000301000D6F0002CB3155ACCF23BFB41A000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000012C012C000D6F0002CB3155FF216154B71C31FA4C09D22390811861A69799BE9B9BBF9494979F929D9BD0C7010100008639021366C54FF74C5D385002800100005000006A9508197904";
$decodedJson = Third_Ys_Sdk::decodeMsg($testBin);

///////////////// 手机退出登录 ////////////////
////////// [测试通过] ////////////// 
$testLogoutJson = '{
    "sign":14716,
    "msgID":102,
    "objType":0,
    "cmdCode":3,
    "objID":0
}';

//$testBin = Third_Ys_Sdk::encodeMsg($testLogoutJson);

$testBin = "FEFEFE7E0014397C00664000000300000000A0C0";

//$decodedJson = Third_Ys_Sdk::decodeMsg($testBin);

//////////////// 手机心跳 ////////////////////
/////////////[测试通过]//////////
$testHeartBeatJson = '{
    "sign":50106,
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

//$testBin = Third_Ys_Sdk::encodeMsg($testHeartBeatJson);
$testBin = "FEFEFE7E0014C3BA0067400000040000000023EF";
//$decodedJson = Third_Ys_Sdk::decodeMsg($testBin);

////////////////// 服务器识别网关 ///////////
//////[测试通过]//////

$testServerIdentifyGateJson = '{
    "sign":0,
    "msgID":104,
    "objType":0,
    "cmdCode":5,
    "objID":0,
    "data":{
        "serverID":"YS-PHP-Server"
    },
    "crc":255
}';

$testBin = Third_Ys_Sdk::encodeMsg($testServerIdentifyGateJson);

$testBin = "FEFEFE7E00B200000068400000050000000059532D5048502D5365727665720000000000008800000301000D6F0002CB3155ACCF23BFB41A000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000012C012C0000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000E000";

$decodedJson = Third_Ys_Sdk::decodeMsg($testBin);

////////////////// 服务器心跳网关 ////////////////////////////////
//////[测试通过]//////

$testServerHeartBeatGateJson = '{
    "sign":0,
    "msgID":104,
    "objType":0,
    "cmdCode":6,
    "objID":0,
    "data":{
        "serverID":"YS-PHP-Server",
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

//$testBin = Third_Ys_Sdk::encodeMsg($testServerHeartBeatGateJson);

$testBin = "FEFEFE7E0014000000684000000600000000F996";

//$decodedJson = Third_Ys_Sdk::decodeMsg($testBin);


/////////// 搜索新终端 ////////
/////// [测试通过]////////

$testSearchNewDevsJson = '{
    "sign":14611,
    "msgID":105,
    "objType":0,
    "cmdCode":10,
    "objID":0,
    "data":{
        "lastSecs":10
    },
    "crc":111
}';

//$testBin = Third_Ys_Sdk::encodeMsg($testSearchNewDevsJson);
$testBin = "FEFEFE7E0014391300694000000A00000000F574";

//$decodedJson = Third_Ys_Sdk::decodeMsg($testBin);

/////////// 列表网关所有终端 ////////
/////// [测试通过] ////
$testListAllDevsJson = '{
    "sign":50106,
    "msgID":105,
    "objType":0,
    "cmdCode":11,
    "objID":0,
    "crc":255
}';

//$testBin = Third_Ys_Sdk::encodeMsg($testListAllDevsJson);

$testBin = "FEFEFE7E003AC3BA00694000000B000000000003DF9C1001000D6F000D317F5D0B2D1001000D6F000D314DFE8DA21001000D6F000D315F51CDB2";

$decodedJson = Third_Ys_Sdk::decodeMsg($testBin);

////////////////////// 网关 读取配置属性测试 ///////////////////////
////【测试通过】

$testConfigReadGate = '{
    "sign":50210,
    "msgID":106,
    "objType":0,
    "cmdCode":192,
    "objID":1,
    "crc":255
}';
// objID = 1 读取网关配置
//$testBin = Third_Ys_Sdk::encodeMsg($testConfigReadGate);

$testBin = "FEFEFE7E00A4C422006A400000C00001000000000001008800000301000D6F0002CB3155ACCF23BFB41A000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000012C012C000D6F0002CB3155FF216154B71C31FA4C09D22390811861A69799BE9B9BBF9494979F929D9BD0C7010100008639021366C54FF74C5D385002800100005000006A950819C753";

//$decodedJson = Third_Ys_Sdk::decodeMsg($testBin);

//////////////////////// 色灯 读取配置属性测试 /////////////////

$testConfigReadLight = '{
    "sign":50210,
    "msgID":107,
    "objType":4,
    "cmdCode":192,
    "objID":57244,
    "crc":255
}';
// objID = 57244 读取色灯配置
$testBin = Third_Ys_Sdk::encodeMsg($testConfigReadLight);

$testBin = "FEFEFE7E0044C422006B400004C0DF9C00000400DF9C00240004000D6F000D317F5D00001001706F00300031000000000000000000000000000100000000E000000073B2";

$decodedJson = Third_Ys_Sdk::decodeMsg($testBin);



