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
        "username":"测3次",
        "phoneNum":"13366666668",
        "gateID":"00d0f60020bc1355"
    },
    "crc":254
}';

//objType = 0 //关
//cmdCode = 1 //手机局域网登录

// $testBin = Third_Ys_Sdk::encodeMsg($testLoginLanJson);

//
$testBin = "FEFEFE7E00FA04D200654000000100000000366434623030333336623231000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000003133333636363636363638000000000000D0F60020BC135578B2008800000301000D6F0002CB3155ACCF23BFB41A000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000012C012C000D6F0002CB3155FF216154B71C31FA4C09D22390811861A69799BE9B9BBF9494979F929D9BD0C7010100008639021366C54FF74C5D385002800100005000006A9508198BA1";

// $decodedJson = Third_Ys_Sdk::decodeMsg($testBin);

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
        "username":"测试4",
        "phoneNum":"13366666666",
        "gateID":"00d0f60020bc1355"
    },
    "crc":555
}';

//fefefe7e006c0000006580000002000000007465737400000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000031333336363636363636360000000000000d6f0002cb315500ff

// $testBin = Third_Ys_Sdk::encodeMsg($testLoginServerJson);

$testBin = "FEFEFE7E006C000000654000000200001008366434623862643530303334000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000003133333636363636363636000000000000D0F60020BC1355B3A8";
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

// $testBin = Third_Ys_Sdk::encodeMsg($testLogoutJson);

$testBin = "FEFEFE7E0014397C00664000000300000000A0C0";

// $decodedJson = Third_Ys_Sdk::decodeMsg($testBin);

//////////////// 【手机心跳】 ////////////////////
/////////////[测试通过]//////////
$testHeartBeatJson = '{
    "sign":50210,
    "msgID":103,
    "objType":0,
    "cmdCode":4,
    "objID":0,
    "data":{
        "phoneNum":"13366666666",
        "year":2018,
        "month":1,
        "day":21,
        "hour":14,
        "minute":38,
        "second":10,
        "weekday":7,
        "hb":300
    },
    "crc":255
}';

// $testBin = Third_Ys_Sdk::encodeMsg($testHeartBeatJson);
$testBin = "FEFEFE7E001EFFFF000120000420FFFE000182976101000111060002BB57";
// $decodedJson = Third_Ys_Sdk::decodeMsg($testBin);

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

$testBin = "FEFEFE7E00B200000068400000050000000059532D5048502D5365727665720000000000008800000301000D6F0002CB3155ACCF23BFB41A000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000012C012C0000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000E000";
//fefefe7e00b200000068400000050000000059532d5048502d5365727665720000000000008800000301000d6f00030c617daccf23bfa4d40100000000000000000000000000000000000000000000000000000000000000621176845bb600000000000000000000012c012c000d6f000d3150f735693801000d6f000d317a3e4fe55e02000d6f000d319f0bd9588201000d6f000d31950d694c6001000d6f000d315cbe0be54001000d6f000d31999a2970

// $decodedJson = Third_Ys_Sdk::decodeMsg($testBin);

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

// $testBin = Third_Ys_Sdk::encodeMsg($testServerHeartBeatGateJson);

$testBin = "FEFEFE7E0014000000684000000600000000F996";

// $decodedJson = Third_Ys_Sdk::decodeMsg($testBin);


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

// $testBin = Third_Ys_Sdk::encodeMsg($testSearchNewDevsJson);
$testBin = "FEFEFE7E0014391300694000000A00000000F574";

// $decodedJson = Third_Ys_Sdk::decodeMsg($testBin);

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

// $testBin = Third_Ys_Sdk::encodeMsg($testListAllDevsJson);

$testBin = "FEFEFE7E00BEC42200694000000B00000000000EB1E32103000D6F000D3152692EB03603000D6F000D319BE95CF73003000D6F0003B76BCBDB8C3403000D6F000D3194FD4D026001000D6F000D3195A582976101000D6F000D31B00A0B6D8201000D6F000D333380637F5E02000D6F000D332F689DD01001000D6F000D314DFE77401001000D6F000D317F5D3BA01001000D6F000D315F51931D4001000D6F000D31955C8C872003000D6F000D315D3E14C53801000D6F000D31938A2142";

// $decodedJson = Third_Ys_Sdk::decodeMsg($testBin);

/*
{
  "msgLen": 178,
  "sign": 50210,
  "msgID": 105,
  "objType": 0,
  "cmdCode": 11,
  "objID": 0,
  "crc": 33600,
  "data": {
    "dev0ID": 40400,
    "dev0Type": 16,
    "dev0Reserved": 1,
    "dev0MAC": "00d0f600d013d4ef",
    "dev1ID": 30528,
    "dev1Type": 16,
    "dev1Reserved": 1,
    "dev1MAC": "00d0f600d013f7d5",
    "dev2ID": 15264,
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
    "dev7ID": 19714,
    "dev7Type": 96,
    "dev7Reserved": 1,
    "dev7MAC": "00d0f600d013595a",
    "dev8ID": 33431,
    "dev8Type": 97,
    "dev8Reserved": 1,
    "dev8MAC": "00d0f600d0130ba0",
    "dev9ID": 5317,
    "dev9Type": 56,
    "dev9Reserved": 1,
    "dev9MAC": "00d0f600d01339a8",
    "dev10ID": 2925,
    "dev10Type": 130,
    "dev10Reserved": 1,
    "dev10MAC": "00d0f600d0333308",
    "dev11ID": 25471,
    "dev11Type": 94,
    "dev11Reserved": 2,
    "dev11MAC": "00d0f600d033f286",
    "dev12ID": 22800,
    "dev12Type": 64,
    "dev12Reserved": 1,
    "dev12MAC": "00d0f600d01359c5",
    "crc": 33600,
    "devNum": 13
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
// $testBin = Third_Ys_Sdk::encodeMsg($testConfigReadGate);

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
// $testBin = Third_Ys_Sdk::encodeMsg($testConfigReadDev);

$testBin = "FEFEFE7E0044C422006B400004C0DF9C00000400DF9C00240004000D6F000D317F5D00001001706F00300031000000000000000000000000000100000000E000000073B2";
//fefefe7e0012c422006b80000410df9c000000010000000000ff
// $decodedJson = Third_Ys_Sdk::decodeMsg($testBin);

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

$testBin = "FEFEFE7E02D2C422006C400004C0FFFF0000000E0400B1E300240004000D6F000D31526900002103706F5F005173003000310000000000000000000100000000E030000004002EB000240004000D6F000D319BE90000360360C5666F8D34003000310000000000000000000100000000E040000004005CF700240010000D6F0003B76BCB00003003706F906563A7003000310000000000000000000100000000E060000313040B2D23048DA23324B1E30400DB8C00240008000D6F000D3194FD00003403706F63A78D34003000310000000000000000000100000000E05000011304DF9C04004D0200240004000D6F000D3195A50000600195E878C10030003100000000000000000000000100000000E07000000400829700240004000D6F000D31B00A000061017EA259160030003100000000000000000000000100000000E080000004000B6D00240004000D6F000D333380000082016E296E7F5EA6003000310000000000000000000100000000E0A000000400637F00240004000D6F000D332F6800005E027A7A8C03906563A700300031000000000000000100000000E0B0000004009DD000240004000D6F000D314DFE00001001706F00300031000000000000000000000000000100000000E00000000400774000240004000D6F000D317F5D00001001706F00300032000000000000000000000000010100000000E010000004003BA000240004000D6F000D315F5100001001706F00300033000000000000000000000000020100000000E02000000400931D00240004000D6F000D31955C000040017A975E180030003100000000000000000000000100000000E0C0000004008C8700240004000D6F000D315D3E00002003706F5F005173003000310000000000000000000100000000E0D00000040014C500240010000D6F000D31938A000038017A975E188D34003000310000000000000000000100000000E0900003130459101304931D1304AA1B3184";

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
// $testBin = Third_Ys_Sdk::encodeMsg($testConfigReadAllSliceDev);

$testBin = "FEFEFE7E01DEC422006C4090FFC0FFFF000200030B00B0010090000056DE5BB600000000000000000000000000000000000000000000000000000000010000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000B00B002009000007528991000000000000000000000000000000000000000000000000000000000010000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000B00B003009000005A314E5000000000000000000000000000000000000000000000000000000000010000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000003752";

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

$testBin = "FEFEFE7E02DEC422006C4080FFC0FFFF0001000804008DA200240004000D6F000D315F5100001001706F00300033000000000000000000000000020100000000E02000000400B1E300240004000D6F000D31526900002103706F5F005173003000310000000000000000000100000000E030000004002EB000240004000D6F000D319BE90000360360C5666F8D34003000310000000000000000000100000000E04000000400DB8C00240008000D6F000D3194FD00003403706F63A78D34003000310000000000000000000100000000E05000011304DF9C04005CF700240010000D6F0003B76BCB00003003706F906563A7003000310000000000000000000100000000E060000313040B2D23048DA23324B1E30B00B0000090000079BB5BB600000000000000000000000000000000000000000000000000000000010000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000B00B0010090000056DE5BB600000000000000000000000000000000000000000000000000000000010000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000B00B002009000007528991000000000000000000000000000000000000000000000000000000000010000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000002D38";

// $decodedJson = Third_Ys_Sdk::decodeMsg($testBin);

///////////////////////// 【列表所有同类对象ID】 //////////////////
//[测试通过]
$testListObjID = '{
    "sign":50210,
    "msgID":110,
    "objType":4,
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

////////////////////// 【删除站点】 ////////////////////

$deleteDevice = '{
    "sign":50210,
    "msgID":118,
    "objType":4,
    "cmdCode":2,
    "data":{"deleteIDArr":[43547]},
    "crc":255
}';

// $testBin = Third_Ys_Sdk::encodeMsg($deleteDevice);

////////////////////// 【进入站点识别】 /////////////////

//目前只支持对一个站点进行识别
$identifyDevice = '{
    "sign":50210,
    "msgID":119,
    "objType":4,
    "cmdCode":4,
    "data":{"deviceIDArr":[45539]},
    "crc":255
}';

// $testBin = Third_Ys_Sdk::encodeMsg($identifyDevice);

////////////////////// 【退出站点识别】 /////////////////

$exitIdentifyDevice = '{
    "sign":50210,
    "msgID":119,
    "objType":4,
    "cmdCode":5,
    "data":{"deviceIDArr":[30528]},
    "crc":255
}';

// $testBin = Third_Ys_Sdk::encodeMsg($exitIdentifyDevice);

//deleteIDArr 要删除的站点数组
///////////////////////// 【单站点遥控】//////////////
//色灯
$testLightControl ='{
    "sign":50210,
    "msgID":111,
    "objType":4,
    "cmdCode":16,
    "objID":15264,
    "data":{
        "subCmdNum":1,
        "subCmdArr":[
            {"devType":16,"subDevNum":1,"powerOn":1,"colorH":64,"colorS":64,"colorB":254}
        ]
    },
    "crc":255
}';

//objID0:30528;objID1:40400;objID2:15264 objID3:45539[开关]
// objType=4 终端设备对象类型为4
// cmdCode=16 命令码遥控为16
// devType 设备类型 16 为灯
// subDevNum 子设备号 灯的情况下为1
// powerOn ： 1 开启 0 关闭
// colorH/colorS/colorB 颜色

// $testBin = Third_Ys_Sdk::encodeMsg($testLightControl);

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

//开关贴
$testSwitchStickerControl ='{
    "sign":50210,
    "msgID":114,
    "objType":4,
    "cmdCode":16,
    "objID":56204,
    "data":{
        "subCmdNum":1,
        "subCmdArr":[
            {"devType":52,"subDevNum":2,"binding":0,"targetID":2861,"targetType":4,"targetSubNum":0}
        ]
    },
    "crc":255
}';

// devType 设备类型 52 为开关贴
// subDevNum 子设备号 1/2/3代表遥控器三组按钮
// binding 1=绑定 0=解绑定
// targetType 要绑定的目标设备类型 4=终端设备
// targetID 要绑定或解绑的目标设备ID
// targetSubNum 目标子设备编号 0代表所有


//门磁 红外 煤气 设置告警命令
$testAlarmDevControl = '{
    "sign":50210,
    "msgID":115,
    "objType":4,
    "cmdCode":16,
    "objID":19714,
    "data":{
        "subCmdNum":1,
        "subCmdArr":[
            {"devType":96,"subDevNum":1,"subCmdCode":2,"securityReport":1,"realReport":1,"nightMode":0}
        ]
    }
}';


//subCmdCode 1=绑定 0=解绑定 2=控制
//devType 96/97/98=门磁 红外 煤气
//subDevNum 子设备号 默认1
//securityRepot / realReport / nightMode 告警模式 实时通知 夜灯模式 1=启用 0=关闭

// $testBin = Third_Ys_Sdk::encodeMsg($testAlarmDevControl);

$testBin = "FEFEFE7E001EFFFF000120000420FFFE00010B6D8201000111002D5B3016";

// $decodedJson = Third_Ys_Sdk::decodeMsg($testBin);

//门磁 红外 煤气 绑定、解绑定命令
$testAlarmDevBindControl = '{
    "sign":50210,
    "msgID":116,
    "objType":4,
    "cmdCode":16,
    "objID":19714,
    "data":{
        "subCmdNum":1,
        "subCmdArr":[
            {"devType":96,"subDevNum":1,"subCmdCode":1,"targetID":2861,"targetType":4,"targetSubNum":0}
        ]
    }
}';

//devType 96/97/98=门磁 红外 煤气
// $testBin = Third_Ys_Sdk::encodeMsg($testAlarmDevBindControl);

$testBin = "FEFEFE7E0014C422006F400004100B2D00002D37";

// $decodedJson = Third_Ys_Sdk::decodeMsg($testBin);


///////////////【窗帘贴绑定窗帘】///////////////////

$testJson = '{
    "sign":50210,
    "msgID":120,
    "objType":4,
    "cmdCode":16,
    "objID":5317,
    "data":{
        "subCmdNum":1,
        "subCmdArr":[
            {"devType":56,"subDevNum":1,"binding":1,"targetID":43547,"targetType":4,"targetSubNum":0}
        ]
    },
    "crc":255
}';


//objID 所要操作的窗帘控制帖ID
// $testBin = Third_Ys_Sdk::encodeMsg($testJson);


/////////////// 【窗帘控制器】//////////////////

//关闭窗帘
$testJson = '{
    "sign":50210,
    "msgID":120,
    "objType":4,
    "cmdCode":16,
    "objID":43547,
    "data":{
        "subCmdNum":1,
        "subCmdArr":[
            {"devType":64,"subDevNum":1,"subCmdCode":0}
        ]
    },
    "crc":255
}';
//32212
//subCmdCode 0=关闭窗帘 
//objID 所要操作的窗帘控制器ID
// $testBin = Third_Ys_Sdk::encodeMsg($testJson);

//移动到任意指定位置
$testJson = '{
    "sign":50210,
    "msgID":120,
    "objType":4,
    "cmdCode":16,
    "objID":43547,
    "data":{
        "subCmdNum":1,
        "subCmdArr":[
            {"devType":64,"subDevNum":1,"subCmdCode":1,"pos":16}
        ]
    },
    "crc":255
}';

//subCmdCode 1=移动到任意位置
//pos 0-240 
//objID 所要操作的窗帘控制器ID
// $testBin = Third_Ys_Sdk::encodeMsg($testJson);

//设置和取消最大行程
$testJson = '{
    "sign":50210,
    "msgID":120,
    "objType":4,
    "cmdCode":16,
    "objID":43547,
    "data":{
        "subCmdNum":1,
        "subCmdArr":[
            {"devType":64,"subDevNum":1,"subCmdCode":4,"set":1}
        ]
    },
    "crc":255
}';

//subCmdCode 4=设置和取消最大行程
//set 1=设置当前位置为最大行程 0=取消最大行程
//objID 所要操作的窗帘控制器ID

// $testBin = Third_Ys_Sdk::encodeMsg($testJson);

//设置中间行程点
$testJson = '{
    "sign":50210,
    "msgID":120,
    "objType":4,
    "cmdCode":16,
    "objID":43547,
    "data":{
        "subCmdNum":1,
        "subCmdArr":[
            {"devType":64,"subDevNum":1,"subCmdCode":5,"pos1":1,"pos2":1,"pos3":0}
        ]
    },
    "crc":255
}';

//subCmdCode 5 =设置和取消行程点
//pos1/2/3 分别代表1/4 1/2 3/4行程点，1表示使能行程点，0表示禁止行程点 
//objID 所要操作的窗帘控制器ID
// $testBin = Third_Ys_Sdk::encodeMsg($testJson);

//运行到下一个行程点
$testJson = '{
    "sign":50210,
    "msgID":120,
    "objType":4,
    "cmdCode":16,
    "objID":22800,
    "data":{
        "subCmdNum":1,
        "subCmdArr":[
            {"devType":64,"subDevNum":1,"subCmdCode":6,"open":1}
        ]
    },
    "crc":255
}';

//subCmdCode 6 =运行到下一行程点
//open 1=开窗帘到下一行程点 0=关窗帘到下一行程点
//objID 所要操作的窗帘控制器ID

// $testBin = Third_Ys_Sdk::encodeMsg($testJson);

//停止运行 强制回零 设置电机反向
$testJson = '{
    "sign":50210,
    "msgID":120,
    "objType":4,
    "cmdCode":16,
    "objID":22800,
    "data":{
        "subCmdNum":1,
        "subCmdArr":[
            {"devType":64,"subDevNum":1,"subCmdCode":7}
        ]
    },
    "crc":255
}';

//subCmdCode 7 停止运行 10 强制回零 11 电机反向

$testBin = Third_Ys_Sdk::encodeMsg($testJson);

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

$testBin = "FEFEFE7E001EFFFF000120000420FFFE00014D026001000111060002A79B";

// $decodedJson = Third_Ys_Sdk::decodeMsg($testBin);


/////////////////////// 【创建组】 ///////////////////////////

$testCreateGroup = '{
    "sign":50210,
    "msgID":140,
    "objType":5,
    "cmdCode":1,
    "objID":0,
    "data":{
        "name":"卧室",
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


//objType 5 = 分组
//cmdCode 1 = 创建
//objID 无关

//////////////////////【创建情景】 ////////////////

//情景模式

$testCreateTaskTable = '{
    "sign":50210,
    "msgID":111,
    "objType":11,
    "cmdCode":1,
    "objID":0,
    "data":{
        "name":"周末",
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
// cmdCode = 1 新建
// objID 所要修改的任务表

//data->type 1=系统情景模式 2=闹钟 3=进入地址围栏 4=离开地址围栏
//data->devArr 该任务表包含的操作设备列表命令
//data->devArr->devID 终端设备ID
//data->devArr->devSubCmdArr 终端设备的子命令数组，请参照终端设备操作命令-终端站点遥控中的子命令定义
//data->staticSceneArr 静态场景ID列表
//data->dynSceneArr 动态场景ID列表

// $testBin = Third_Ys_Sdk::encodeMsg($testCreateTaskTable);

$testBin = "FEFEFE7E0016C422006F40000B0100000000B0085866";

// $decodedJson = Third_Ys_Sdk::decodeMsg($testBin);

//【创建闹钟】

$testCreateClockTask = '{
    "sign":50210,
    "msgID":130,
    "objType":11,
    "cmdCode":1,
    "objID":0,
    "data":{
        "name":"起床",
        "phone":13366666666,
        "type":2,
        "clockSet":{
            "repeat":0,
            "day1":1,
            "day2":0,
            "day3":1,
            "day4":0,
            "day5":1,
            "day6":0,
            "day7":0,
            "weekday":7,
            "hour":23,
            "minute":59,
            "second":59
        },
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
// cmdCode = 1 新建
// objID 所要修改的任务表

//data->clockSet 闹钟设置
//data->clockSet->repeat 是否多次 1=多次 0=单次
//data->clockSet->day1-7: 周一至周日  repeat为多次的时候生效
//data->clockSet->weekday: 有效值为1-7 代表周一至周日 repeat为单次的时候生效
//data->clockSet->hour: 时 0-23
//data->clockSet->minute: 分 0-59
//data->clockSet->second: 秒0-59

// $testBin = Third_Ys_Sdk::encodeMsg($testCreateClockTask);

$testBin = "FEFEFE7E0016C422006F40000B0100000000B0085866";

// $decodedJson = Third_Ys_Sdk::decodeMsg($testBin);

//////////////////////【修改情景】 ////////////////

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

// $testBin = Third_Ys_Sdk::encodeMsg($testModifyTaskTable);

$testBin = "FEFEFE7E0014C422006F40000B03B000000029E5";

// $decodedJson = Third_Ys_Sdk::decodeMsg($testBin);

//【修改闹钟】
//与创建闹钟相同，只需要调整objID为所要修改的闹钟ID即可

//////////////////////【删除情景】 ////////////////

$testDeleteTaskTable = '{
    "sign":50210,
    "msgID":111,
    "objType":11,
    "cmdCode":2,
    "objID":45064,
    "crc":255
}';

// objType = 11 任务表
// cmdCode = 3 修改
// objID 所要删除的任务表ID


// $testBin = Third_Ys_Sdk::encodeMsg($testDeleteTaskTable);

$testBin = "FEFEFE7E0014C422006F40000B03B000000029E5";

// $decodedJson = Third_Ys_Sdk::decodeMsg($testBin);

/////////////////////【召唤任务表】//////////////////////

$testStartTaskTable = '{
    "sign":50210,
    "msgID":112,
    "objType":11,
    "cmdCode":16,
    "objID":45056,
    "crc":255
}';

// $testBin = Third_Ys_Sdk::encodeMsg($testStartTaskTable);

$testBin = "FEFEFE7E0014C422006F40000B03B000000029E5";

// $decodedJson = Third_Ys_Sdk::decodeMsg($testBin);

//////////////////////////////////////////////////////
$testBin = "FEFEFE7E001EFFFF000120000420FFFE0001829761010001110600009B15";

// $decodedJson = Third_Ys_Sdk::decodeMsg($testBin);


