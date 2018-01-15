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

$testBin = Third_Ys_Sdk::encodeMsg($testLoginLanJson);

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

$testBin = Third_Ys_Sdk::encodeMsg($testLoginServerJson);

$testBin = "FEFEFE7E00FA0000006540000002000000007465737400000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000031333336363636363636360000000000000D6F0002CB3155131C008800000301000D6F0002CB3155ACCF23BFB41A000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000012C012C000D6F0002CB3155FF216154B71C31FA4C09D22390811861A69799BE9B9BBF9494979F929D9BD0C7010100008639021366C54FF74C5D385002800100005000006A9508197904";
$decodedJson = Third_Ys_Sdk::decodeMsg($testBin);

///////////////// 【手机退出登录】 ////////////////
////////// [测试通过] ////////////// 
$testLogoutJson = '{
    "sign":14716,
    "msgID":102,
    "objType":0,
    "cmdCode":3,
    "objID":0
}';

$testBin = Third_Ys_Sdk::encodeMsg($testLogoutJson);

$testBin = "FEFEFE7E0014397C00664000000300000000A0C0";

$decodedJson = Third_Ys_Sdk::decodeMsg($testBin);

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

$testBin = Third_Ys_Sdk::encodeMsg($testHeartBeatJson);
$testBin = "FEFEFE7E0014C3BA0067400000040000000023EF";
$decodedJson = Third_Ys_Sdk::decodeMsg($testBin);

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

$testBin = Third_Ys_Sdk::encodeMsg($testServerIdentifyGateJson);

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

$testBin = Third_Ys_Sdk::encodeMsg($testServerHeartBeatGateJson);

$testBin = "FEFEFE7E0014000000684000000600000000F996";

$decodedJson = Third_Ys_Sdk::decodeMsg($testBin);


/////////// 【搜索新终端】 ////////
/////// [测试通过]////////

$testSearchNewDevsJson = '{
    "sign":50210,
    "msgID":105,
    "objType":0,
    "cmdCode":10,
    "objID":0,
    "data":{
        "lastSecs":120
    },
    "crc":255
}';

//fefefe7e0014c42200698000000a0000001e00ff

$testBin = Third_Ys_Sdk::encodeMsg($testSearchNewDevsJson);
$testBin = "FEFEFE7E0014391300694000000A00000000F574";

$decodedJson = Third_Ys_Sdk::decodeMsg($testBin);

/////////// 【列表网关所有终端】 ////////
/////// [测试通过] ////
$testListAllDevsJson = '{
    "sign":50210,
    "msgID":105,
    "objType":0,
    "cmdCode":11,
    "objID":0,
    "crc":255
}';

//fefefe7e0012c42200698000000b000000ff

$testBin = Third_Ys_Sdk::encodeMsg($testListAllDevsJson);

$testBin = "FEFEFE7E00B2C42200694000000B00000000000DDF9C1001000D6F000D317F5D0B2D1001000D6F000D314DFE8DA21001000D6F000D315F51B1E32103000D6F000D3152692EB03603000D6F000D319BE9DB8C3403000D6F000D3194FD5CF73003000D6F0003B76BCB4D026001000D6F000D3195A582976101000D6F000D31B00A14C53801000D6F000D31938A0B6D8201000D6F000D333380637F5E02000D6F000D332F6859104001000D6F000D31955CD4EA";

$decodedJson = Third_Ys_Sdk::decodeMsg($testBin);

/*
 * {
  "msgLen": 106,
  "sign": 50210,
  "msgID": 105,
  "objType": 0,
  "cmdCode": 11,
  "objID": 0,
  "crc": 20079,
  "data": {
    "dev0ID": 57244,
    "dev0Type": 16,
    "dev0Reserved": 1,
    "dev0MAC": "00d0f600d013f7d5",
    "dev1ID": 2861,
    "dev1Type": 16,
    "dev1Reserved": 1,
    "dev1MAC": "00d0f600d013d4ef",
    "dev2ID": 36258,
    "dev2Type": 16,
    "dev2Reserved": 1,
    "dev2MAC": "00d0f600d013f515",
    "dev3ID": 45539,
    "dev3Type": 33,
    "dev3Reserved": 3,
    "dev3MAC": "00d0f600d0132596",
    "dev4ID": 11952,
    "dev4Type": 54,
    "dev4Reserved": 3,
    "dev4MAC": "00d0f600d013b99e",
    "dev5ID": 56204,
    "dev5Type": 52,
    "dev5Reserved": 3,
    "dev5MAC": "00d0f600d01349df",
    "dev6ID": 23799,
    "dev6Type": 48,
    "dev6Reserved": 3,
    "dev6MAC": "00d0f600307bb6bc",
    "crc": 20079,
    "devNum": 7
  }
}
 */

///////////////////// 【控制网关命名】 ///////////////////

$testSetGateName ='{
    "sign":50210,
    "msgID":112,
    "objType":0,
    "cmdCode":
}';

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
$testBin = Third_Ys_Sdk::encodeMsg($testConfigReadGate);

$testBin = "FEFEFE7E00A4C422006A400000C00001000000000001008800000301000D6F0002CB3155ACCF23BFB41A000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000012C012C000D6F0002CB3155FF216154B71C31FA4C09D22390811861A69799BE9B9BBF9494979F929D9BD0C7010100008639021366C54FF74C5D385002800100005000006A950819C753";

$decodedJson = Third_Ys_Sdk::decodeMsg($testBin);

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
$testBin = Third_Ys_Sdk::encodeMsg($testConfigReadDev);

$testBin = "FEFEFE7E0044C422006B400004C0DF9C00000400DF9C00240004000D6F000D317F5D00001001706F00300031000000000000000000000000000100000000E000000073B2";
//fefefe7e0012c422006b80000410df9c000000010000000000ff
$decodedJson = Third_Ys_Sdk::decodeMsg($testBin);

////////////////////////// 【读取同类别所有设备属性测试】 /////////////////
//// 注：该操作可能触发分片消息，请注意分片的设置
//// 1. 初始读取指令和其他指令没有区别，因为此时不知道是否会触发分片
//// 2. 如果初始返回的消息中携带了分片 sliceM = 1, 则说明需要进行分片
///// 那么后续的手机端发送的指令需要指定分片序号sliceSeq
//// 3. 分片消息返回中携带sliceID，后续发送的分片消息中需要设置sliceSeq=sliceID+1，然后发送
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
$testBin = Third_Ys_Sdk::encodeMsg($testConfigReadAllDev);

$testBin = "FEFEFE7E0176C422006C400004C0FFFF000000070400DF9C00240004000D6F000D317F5D00001001706F00300031000000000000000000000000000100000000E000000004000B2D00240004000D6F000D314DFE00001001706F00300032000000000000000000000000010100000000E010000004008DA200240004000D6F000D315F5100001001706F00300033000000000000000000000000020100000000E02000000400B1E300240004000D6F000D31526900002103706F5F005173003000310000000000000000000100000000E030000004002EB000240004000D6F000D319BE90000360360C5666F8D34003000310000000000000000000100000000E04000000400DB8C00240008000D6F000D3194FD00003403706F63A78D34003000310000000000000000000100000000E05000011304DF9C04005CF700240010000D6F0003B76BCB00003003706F906563A7003000310000000000000000000100000000E060000313040B2D23048DA23324B1E30168";

$decodedJson = Third_Ys_Sdk::decodeMsg($testBin);

//////////// **** 携带分片读取同类别所有设备属性
//// 注：sliceSeq 为分片序号；如果返回的消息中包含了sliceT=1，则表明分片消息已经结束，则不需要再发送分片请求。
$testConfigReadAllSliceDev = '{
    "sign":50210,
    "msgID":108,
    "objType":255,
    "cmdCode":192,
    "objID":65535,
    "sliceSeq":2,
    "crc":255
}';
// objID = 65535 读取所有
// sliceSeq = 1 分片发送序号为1的指令，后续递增该序号
$testBin = Third_Ys_Sdk::encodeMsg($testConfigReadAllSliceDev);

$testBin = "FEFEFE7E01DEC422006C4090FFC0FFFF000200030B00B0010090000056DE5BB600000000000000000000000000000000000000000000000000000000010000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000B00B002009000007528991000000000000000000000000000000000000000000000000000000000010000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000B00B003009000005A314E5000000000000000000000000000000000000000000000000000000000010000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000003752";

$decodedJson = Third_Ys_Sdk::decodeMsg($testBin);

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
$testBin = Third_Ys_Sdk::encodeMsg($testConfigReadAllDev);

$testBin = "FEFEFE7E02DEC422006C4080FFC0FFFF0001000804008DA200240004000D6F000D315F5100001001706F00300033000000000000000000000000020100000000E02000000400B1E300240004000D6F000D31526900002103706F5F005173003000310000000000000000000100000000E030000004002EB000240004000D6F000D319BE90000360360C5666F8D34003000310000000000000000000100000000E04000000400DB8C00240008000D6F000D3194FD00003403706F63A78D34003000310000000000000000000100000000E05000011304DF9C04005CF700240010000D6F0003B76BCB00003003706F906563A7003000310000000000000000000100000000E060000313040B2D23048DA23324B1E30B00B0000090000079BB5BB600000000000000000000000000000000000000000000000000000000010000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000B00B0010090000056DE5BB600000000000000000000000000000000000000000000000000000000010000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000B00B002009000007528991000000000000000000000000000000000000000000000000000000000010000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000002D38";

$decodedJson = Third_Ys_Sdk::decodeMsg($testBin);

///////////////////////// 【列表所有同类对象ID】 //////////////////
//[测试通过]
$testListObjID = '{
    "sign":50210,
    "msgID":110,
    "objType":11,
    "cmdCode":195,
    "objID":65535,
    "crc":255 
}';

// objType = 5 分组。注意查看帮助手册中的
// cmdCode = 195 列表所有对象ID
// objID = 65535 默认，无需修改

$testBin = Third_Ys_Sdk::encodeMsg($testListObjID);

$testBin = "FEFEFE7E001EC422006E40000BC3FFFF00000004B000B001B002B0033CCD";

$decodedJson = Third_Ys_Sdk::decodeMsg($testBin);

///////////////////////// 【单站点遥控】//////////////
//色灯
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

//objID0:57244;objID1:2861;objID2:36258 objID3:45539[开关]
// objType=4 终端设备对象类型为4
// cmdCode=16 命令码遥控为16
// devType 设备类型 16 为灯
// subDevNum 子设备号 灯的情况下为1
// powerOn ： 1 开启 0 关闭
// colorH/colorS/colorB 颜色

//墙壁开关
$testSwitchControl ='{
    "sign":50210,
    "msgID":112,
    "objType":4,
    "cmdCode":16,
    "objID":45539,
    "data":{
        "subCmdNum":1,
        "subCmdArr":[
            {"devType":33,"subDevNum":0,"powerOn":0}
        ]
    },
    "crc":255
}';

// devType 设备类型 33 为零火线多路触摸墙壁开关 32=单火线多路触摸开关
// subDevNum 子设备号 0=全部开关键 1/2/3分别指第1/2/3开关键
// powerOn ： 1 开启 0 关闭

//灯遥控器

$testLightRemoterControl ='{
    "sign":50210,
    "msgID":113,
    "objType":4,
    "cmdCode":16,
    "objID":23799,
    "data":{
        "subCmdNum":3,
        "subCmdArr":[
            {"devType":48,"subDevNum":1,"binding":1,"targetID":2861,"targetSubNum":0},
            {"devType":48,"subDevNum":2,"binding":1,"targetID":36258,"targetSubNum":0},
            {"devType":48,"subDevNum":3,"binding":1,"targetID":45539,"targetSubNum":2}
        
        ]
    },
    "crc":255
}';

// devType 设备类型 48 为灯遥控器
// subDevNum 子设备号 1/2/3代表遥控器三组按钮
// binding 1=绑定 0=解绑定
// targetID 要绑定或解绑的目标设备ID
// targetSubNum 目标子设备编号 0代表所有


$testSwitchStickerControl ='{
    "sign":50210,
    "msgID":114,
    "objType":4,
    "cmdCode":16,
    "objID":56204,
    "data":{
        "subCmdNum":1,
        "subCmdArr":[
            {"devType":52,"subDevNum":2,"binding":0,"targetID":2861,"targetSubNum":0}
        ]
    },
    "crc":255
}';

// devType 设备类型 52 为开关贴
// subDevNum 子设备号 1/2/3代表遥控器三组按钮
// binding 1=绑定 0=解绑定
// targetID 要绑定或解绑的目标设备ID
// targetSubNum 目标子设备编号 0代表所有

$testBin = Third_Ys_Sdk::encodeMsg($testSwitchControl);

$testBin = "FEFEFE7E0014C422006F400004100B2D00002D37";

$decodedJson = Third_Ys_Sdk::decodeMsg($testBin);

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

$testBin = "FEFEFE7E0032FFFF000120000420FFFE0003DF9C100100011100FE360B2D100100011100FE368DA2100100011100FE36019F";

$decodedJson = Third_Ys_Sdk::decodeMsg($testBin);


//////////////////////【修改任务表】 ////////////////

//情景模式

$testModifyTaskTable = '{
    "sign":50210,
    "msgID":111,
    "objType":11,
    "cmdCode":3,
    "objID":45056,
    "data":{
        "name":"离开家1",
        "phone":13366666666,
        "type":1,
        "devArr":[
            {"devID":57244,
            "devSubCmdArr":[{"devType":16,"subDevNum":1,"powerOn":1,"colorH":32,"colorS":32,"colorB":128}]},
            {"devID":2861,
            "devSubCmdArr":[{"devType":16,"subDevNum":1,"powerOn":1,"colorH":32,"colorS":32,"colorB":128}]}
        ],
        "staticSceneArr":[
        ],
        "dynSceneArr":[
        ]
    },
    "crc":255
}';

// objType = 11 任务表
// cmdCode = 3 修改
// objID 所要修改的任务表

//data->type 1=系统情景模式 2=闹钟 3=进入地址围栏 4=离开地址围栏
//data->devArr 该任务表包含的操作设备列表命令
//data->devArr->devID 终端设备ID
//data->devArr->devSubCmdArr 终端设备的子命令数组，请参照终端设备操作命令-终端站点遥控中的子命令定义
//data->staticSceneArr 静态场景ID列表
//data->dynSceneArr 动态场景ID列表

$testBin = Third_Ys_Sdk::encodeMsg($testModifyTaskTable);

$testBin = "FEFEFE7E0014C422006F40000B03B000000029E5";

$decodedJson = Third_Ys_Sdk::decodeMsg($testBin);

/////////////////////【召唤任务表】//////////////////////

$testStartTaskTable = '{
    "sign":50210,
    "msgID":112,
    "objType":11,
    "cmdCode":16,
    "objID":45056,
    "crc":255
}';

$testBin = Third_Ys_Sdk::encodeMsg($testStartTaskTable);

$testBin = "FEFEFE7E0014C422006F40000B03B000000029E5";

// $decodedJson = Third_Ys_Sdk::decodeMsg($testBin);

//////////////////////////////////////////////////////
$testBin = "FEFEFE7E001EFFFF000120000420FFFE0001829761010001110600009B15";

$decodedJson = Third_Ys_Sdk::decodeMsg($testBin);


