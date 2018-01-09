<?php

include 'YSProtocol.class.php';
// include 'YSProtocol.class.min.php';

///////////////// 测试 /////////////////////

//////////////// 【手机局域网登录】 /////////////////
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

$decodedJson = Third_Ys_Sdk::decodeMsg($testBin);

//echo json_decode($decodedJson)->data->username;

//////////////// 【手机服务器登录】 //////////////////
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

// $testBin = Third_Ys_Sdk::encodeMsg($testLoginServerJson);

$testBin = "FEFEFE7E00FA0000006540000002000000007465737400000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000031333336363636363636360000000000000D6F0002CB3155131C008800000301000D6F0002CB3155ACCF23BFB41A000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000012C012C000D6F0002CB3155FF216154B71C31FA4C09D22390811861A69799BE9B9BBF9494979F929D9BD0C7010100008639021366C54FF74C5D385002800100005000006A9508197904";
// $decodedJson = Third_Ys_Sdk::decodeMsg($testBin);

///////////////// 【手机退出登录】 ////////////////
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

//////////////// 【手机心跳】 ////////////////////
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

////////////////// 【服务器识别网关】 ///////////
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

// $testBin = Third_Ys_Sdk::encodeMsg($testServerIdentifyGateJson);

$testBin = "fefefe7e00b200000067400000050000000059532d5048502d5365727665720000000000008800000301000d6f00030c617daccf23bfa4d40000000000000000000000000000000000000000000000000000000000000000621176845bb600000000000000000000012c012c00000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000008677";

$decodedJson = Third_Ys_Sdk::decodeMsg($testBin);

////////////////// 【服务器心跳网关 】////////////////////////////////
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


/////////// 【搜索新终端】 ////////
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

/////////// 【列表网关所有终端】 ////////
/////// [测试通过] ////
$testListAllDevsJson = '{
    "sign":50106,
    "msgID":105,
    "objType":0,
    "cmdCode":11,
    "objID":0,
    "crc":255
}';

$testBin = Third_Ys_Sdk::encodeMsg($testListAllDevsJson);

$testBin = "FEFEFE7E003AC3BA00694000000B000000000003DF9C1001000D6F000D317F5D0B2D1001000D6F000D314DFE8DA21001000D6F000D315F51CDB2";

$decodedJson = Third_Ys_Sdk::decodeMsg($testBin);

////////////////////// 【网关 读取配置属性测试】 ///////////////////////
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

// $decodedJson = Third_Ys_Sdk::decodeMsg($testBin);

////////////////// 【网关允许手机首次登录】 ///////////////////

$testBin = "FEFEFE7E0012FFFF00012000000700001C4E";

//$decodedJson = Third_Ys_Sdk::decodeMsg($testBin);

//sign:65535
//objType:0
//cmdCode:7

//////////////////////// 【色灯 读取配置属性测试】 /////////////////
//// 【测试通过】
$testConfigReadDev = '{
    "sign":50210,
    "msgID":107,
    "objType":4,
    "cmdCode":192,
    "objID":57244,
    "crc":255
}';
// objID = 57244 读取色灯配置
//$testBin = Third_Ys_Sdk::encodeMsg($testConfigReadDev);

$testBin = "FEFEFE7E0044C422006B400004C0DF9C00000400DF9C00240004000D6F000D317F5D00001001706F00300031000000000000000000000000000100000000E000000073B2";
//fefefe7e0012c422006b80000410df9c000000010000000000ff
// $decodedJson = Third_Ys_Sdk::decodeMsg($testBin);

////////////////////////// 【读取同类别所有设备属性测试】 /////////////////
//// 注：该操作可能触发分片消息，请注意分片的设置
//// 1. 初始读取指令和其他指令没有区别，因为此时不知道是否会触发分片
//// 2. 如果初始返回的消息中携带了分片 sliceM = 1, 则说明需要进行分片，那么后续的手机端发送的指令需要指定分片序号sliceSeq
//// 【测试通过】
$testConfigReadAllDev = '{
    "sign":50210,
    "msgID":108,
    "objType":4,
    "cmdCode":192,
    "objID":65535,
    "crc":255
}';
// objID = 65535 读取所有
// $testBin = Third_Ys_Sdk::encodeMsg($testConfigReadAllDev);

$testBin = "FEFEFE7E00A6C422006C400004C0FFFF000000030400DF9C00240004000D6F000D317F5D00001001706F00300031000000000000000000000000000100000000E000000004000B2D00240004000D6F000D314DFE00001001706F00300032000000000000000000000000010100000000E010000004008DA200240004000D6F000D315F5100001001706F00300033000000000000000000000000020100000000E0200000B2D1";

// $decodedJson = Third_Ys_Sdk::decodeMsg($testBin);

//////////// **** 携带分片读取同类别所有设备属性
//// 注：sliceSeq 为分片序号，每次递增+1；如果返回的消息中包含了sliceT=1，则表明分片消息已经结束，则不需要再发送分片请求。
$testConfigReadAllSliceDev = '{
    "sign":50210,
    "msgID":108,
    "objType":4,
    "cmdCode":192,
    "objID":65535,
    "sliceSeq":1,
    "crc":255
}';
// objID = 65535 读取所有
// sliceSeq = 1 分片发送序号为1的指令，后续递增该序号
// $testBin = Third_Ys_Sdk::encodeMsg($testConfigReadAllSliceDev);

$testBin = "FEFEFE7E00A6C422006B400004C0FFFF000000030400DF9C00240004000D6F000D317F5D00001001706F00300031000000000000000000000000000100000000E000000004000B2D00240004000D6F000D314DFE00001001706F00300032000000000000000000000000010100000000E010000004008DA200240004000D6F000D315F5100001001706F00300033000000000000000000000000020100000000E02000007B6E";

// $decodedJson = Third_Ys_Sdk::decodeMsg($testBin);



///////////////////// 【读取所有配置属性信息】/////////////////////
//// 同上，可能触发分片
$testConfigReadAllDev = '{
    "sign":50210,
    "msgID":109,
    "objType":255,
    "cmdCode":192,
    "objID":65535,
    "crc":255
}';

// objType = 255 读取所有类别
// objID = 65535 读取所有
// $testBin = Third_Ys_Sdk::encodeMsg($testConfigReadAllDev);

$testBin = "FEFEFE7E0136C422006D4000FFC0FFFF0000000400000001008800000301000D6F0002CB3155ACCF23BFB41A000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000012C012C000D6F0002CB3155FF216154B71C31FA4C09D22390811861A69799BE9B9BBF9494979F929D9BD0C7010100008639021366C54FF74C5D385002800100005000006A9508190400DF9C00240004000D6F000D317F5D00001001706F00300031000000000000000000000000000100000000E000000004000B2D00240004000D6F000D314DFE00001001706F00300032000000000000000000000000010100000000E010000004008DA200240004000D6F000D315F5100001001706F00300033000000000000000000000000020100000000E0200000B84F";

// $decodedJson = Third_Ys_Sdk::decodeMsg($testBin);

///////////////////////// 【列表所有同类对象ID】 //////////////////
//[测试通过]
$testListObjID = '{
    "sign":50210,
    "msgID":110,
    "objType":5,
    "cmdCode":195,
    "objID":65535,
    "crc":255 
}';

// objType = 5 分组。注意查看帮助手册中的
// cmdCode = 195 列表所有对象ID
// objID = 65535 默认，无需修改

// $testBin = Third_Ys_Sdk::encodeMsg($testListObjID);

$testBin = "FEFEFE7E001EC422006E40000BC3FFFF00000004B000B001B002B0033CCD";

// $decodedJson = Third_Ys_Sdk::decodeMsg($testBin);

///////////////////////// 【单站点遥控】//////////////
//【JSON格式可能要调整】
$testLightControl ='{
    "sign":50210,
    "msgID":111,
    "objType":4,
    "cmdCode":16,
    "objID":2861,
    "data":{
        "subCmdNum":1,
        "subCmdArr":[
            {"devType":16,"subDevNum":1,"powerOn":1,"colorH":64,"colorS":64,"colorB":254}
        ]
    },
    "crc":255
}';

//objID0:57244;objID1:2861;objID2:36258
// objType=4 终端设备对象类型为4
// cmdCode=16 命令码遥控为16

$testBin = Third_Ys_Sdk::encodeMsg($testLightControl);

$testBin = "FEFEFE7E0014C422006F400004108DA20000C0FD";

// $decodedJson = Third_Ys_Sdk::decodeMsg($testBin);

///////////////////////// 【多站点遥控】//////////////

$testLightControl ='{
    "sign":50210,
    "msgID":111,
    "objType":4,
    "cmdCode":16,
    "objID":65534,
    "data":{
        "devNum":2,
        "devCmdArr":[
            {"devID":57244,
            "devType":16,
            "subDevNum":1,
            "devSubCmdNum":2,
            "devSubCmdArr":[{"powerOn":1,"colorH":32,"colorS":32,"colorB":128},
                        {"powerOn":1,"colorH":32,"colorS":32,"colorB":128}]},
            {"devID":2861,
            "devType":16,
            "subDevNum":1,
            "devSubCmdNum":2,
            "devSubCmdArr":[{"powerOn":1,"colorH":32,"colorS":32,"colorB":128},
                        {"powerOn":1,"colorH":32,"colorS":32,"colorB":128}]}
        ]
    },
    "crc":255
}';

////////////////////// 【设备状态上报】 /////////////////

$testBin = "FEFEFE7E003EFFFF000120000420FFFE0003DF9C1001000290000000114040FE0B2D1001000290000000114040FE8DA2100100029000000011202080C228";

$decodedJson = Third_Ys_Sdk::decodeMsg($testBin);
