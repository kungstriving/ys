# 读取配置属性命令

## 概述

配置属性信息也就是静态存储在网关中的数据库，可以直接读取指定对象的属性信息。

如果对象ID为0xFFFF，代表获取同一类型所有设备的属性信息。

如果对象类型为0XFF，则代表获取网关所有数据。

该命令可能触发分片。

## 指令定义

### 读取单个网关配置信息
---

**描述**：

读取单个网关配置信息

**传入消息格式示例**：

	$testConfigReadGate = '{
    	"sign":50210,
    	"msgID":106,
    	"objType":0,
    	"cmdCode":192,
    	"objID":1,
		"crc":255
	}';

>objType=0 网关

>objID=1 读取网关配置

>cmdCode=192 读取配置


data数据域定义：

无

**返回消息格式示例**：

*正确情况*

	//正确情况
	$testConfigReadResponseJson = '{
		"msgLen":164,
    	"sign":50210,
    	"msgID":106,
    	"objType":0,
    	"cmdCode":192,
    	"objID":1,
		"crc":51027,
    	"data":{
			"cmdRetCode":0,
    	    "dataObjType":0,
    	    "sliceID":0,
    	    "dataObjID":1,
			"gateFixLen":136,
			"gateExtLen":0,
			"protoVer":769,
			"gateID":"00d0f60020bc1355",
			"gateMAC":"cafc32fb4ba1",
			"reserved":"",
			"gateName":"",
			"hbLan":300,
			"hbWLan":300,
			"netData":"00d0f60020bc1355ff1216457bc113afc4902d32091881166a7999ebb9b9fb494979f929d9b90d7c1010000068932031665cf47fc4d583052008100000050000a6598091",
			"crc":51027
    	}
	}';

data数据域定义：

>cmdRetCode: 命令返回错误码 0为正确情况

>dataObjType: 对象类型

>sliceID:分片ID
>
>gateFixLen：网关固定域长度

>gateExtLen：网关扩展域长度

>protoVer:协议版本号

>hbLan:局域网心跳周期，默认300秒

>hbWLan:公网心跳周期，默认300秒

>netData:网络数据

>gateID:要登录的网关设备标识

>gateMAC:网关MAC


### 读取单个终端配置信息
---

**描述**：

读取单个终端的配置信息

**传入消息格式示例**：

	$testConfigReadDev = '{
    	"sign":50210,
    	"msgID":107,
    	"objType":4,
    	"cmdCode":192,
    	"objID":57244,
		"crc":255
	}';

>objType=4 终端

>cmdCode=192 读取配置

>objID=57244 所要读取的色灯设备的ID


data数据域定义：

无

**返回消息格式示例**：

*正确情况*

	//正确情况
	$testConfigReadDevResponse = '{
		"msgLen":68,
    	"sign":50210,
    	"msgID":107,
    	"objType":4,
    	"cmdCode":192,
    	"objID":57244,
		"crc":57344,
    	"data":{
			"cmdRetCode":0,
    	    "dataObjType":4,
    	    "sliceID":0,
    	    "dataObjID":57244,
			"fixLen":36,
			"extLen":4,
			"devID":"00d0f600d013f7d5",
			"parentID":0,
			"devType":4097,
			"devName":"",
			"reserved":0,
			"staSeq":0,
			"softVer":1,
			"startClock":0,	
			"stopClock":0,
			"crc":57344
    	}
	}';

data数据域定义：

>cmdRetCode: 命令返回错误码 0为正确情况

>parentID：父设备ID

>staSeq：同类站点序号

>startClock:定时开闹钟序号

>stopClock:定时关闹钟序号


### 读取同类别所有终端配置信息
---

**描述**：

读取同类别的所有终端设备配置信息，该指令可能触发分片。需要根据返回消息进行判断是否进行分片发送。

分片指令的发送需要严格按照序列发送，只有收到前一分片报文的应答之后，才可以发出下一分片的报文。不允许并发多个分片报文。

手机作为命令发起方，同一个时间段只能发起一次分片文件的传输。

**传入消息格式示例**：

	$testConfigReadAllDev = '{
    	"sign":50210,
    	"msgID":108,
    	"objType":4,
    	"cmdCode":192,
    	"objID":65535,
		"sliceSeq":1,
		"crc":255
	}';

>objType=5 所要读取的类别，终端=4、组=5，请参照操作对象类型定义。如果设置为255，则读取网关上的所有配置信息。

>cmdCode=192 读取配置

>objID=65535 读取该类别所有终端设备的属性

>sliceSeq=1 分片序号，从1开始每次递增1，**只有需要分片读取的时候才添加该字段，初始读取不需要该字段；是否需要该字段由返回消息中的sliceM进行判断，参考返回消息中的说明**


data数据域定义：

无

**返回消息格式示例**：

> 注：1. 如果返回的数据域中携带sliceM=1,则说明需要进行分片发送，那么需要在**10秒**内再次发送读取指令，同时在发送指令中增加sliceSeq=X，其中X为分片序号从1开始，每次递增。

> 2.如果返回的消息中携带sliceT = 1, 则说明分片数据已经结束，则不再需要发送分片读取指令。


*正确情况*

	$testConfigReadAllDevResponse = '{
	  "msgLen": 374,
	  "sign": 50210,
	  "msgID": 108,
	  "objType": 4,
	  "cmdCode": 192,
	  "objID": 65535,
	  "crc": 360,
	  "data": {
	    "cmdRetCode": 0,
	    "objNum": 7,
	    "dataObjArr": [
	      {
	        "objType": 4,
	        "objID": 57244,
	        "objContent": {
	          "devMac": "00d0f600d013f7d5",
	          "parentID": 0,
	          "devType": 16,
	          "devNum": 1,
	          "devName": "灯01",
	          "devSeq": 0,
	          "protoVer": 0,
	          "devSignGroup": 57344,
	          "subCmdNum": 0,
	          "subCmdArr": [
	            
	          ]
	        }
	      },
	      {
	        "objType": 4,
	        "objID": 56204,
	        "objContent": {
	          "devMac": "00d0f600d01349df",
	          "parentID": 0,
	          "devType": 52,
	          "devNum": 3,
	          "devName": "灯控贴01",
	          "devSeq": 0,
	          "protoVer": 0,
	          "devSignGroup": 57424,
	          "subCmdNum": 1,
	          "subCmdArr": [
	            {
	              "subDevNum": 1,
	              "binding": 1,
	              "targetDevSubNum": 0,
	              "targetDevType": 4,
	              "targetObjID": 57244
	            }
	          ]
	        }
	      },
	      {
	        "objType": 4,
	        "objID": 23799,
	        "objContent": {
	          "devMac": "00d0f600307bb6bc",
	          "parentID": 0,
	          "devType": 48,
	          "devNum": 3,
	          "devName": "灯遥控01",
	          "devSeq": 0,
	          "protoVer": 0,
	          "devSignGroup": 57440,
	          "subCmdNum": 3,
	          "subCmdArr": [
	            {
	              "subDevNum": 1,
	              "binding": 1,
	              "targetDevSubNum": 0,
	              "targetDevType": 4,
	              "targetObjID": 2861
	            },
	            {
	              "subDevNum": 2,
	              "binding": 1,
	              "targetDevSubNum": 0,
	              "targetDevType": 4,
	              "targetObjID": 36258
	            },
	            {
	              "subDevNum": 3,
	              "binding": 1,
	              "targetDevSubNum": 2,
	              "targetDevType": 4,
	              "targetObjID": 45539
	            }
	          ]
	        }
	      }
	    ],
	    "crc": 360
	  }
	}';

data数据域定义：

>sliceM: 还需要进行分片处理，不一定每次都有该属性，如果后续没有分片数据，则没有该属性。

>sliceID: 此次接收到数据的分片ID，不一定每次都有该属性，如果不是分片发送的数据，则没有该属性。

>objNum: 此次返回的终端数量

>dataObjArr：所有对象数组

>dataObjArr->objType: 对象类型，参考使用帮助中的类型定义

>dataObjArr->objID：对象ID

>dataObjArr->objContent：该对象的属性信息对象。注意objContent中的内容会根据objType的不同而不同。

>**以下是终端对象的定义**

>dataObjArr->objContent->devMac:该设备的唯一标识

>dataObjArr->objContent->parentID: 该设备的父ID

>dataObjArr->objContent->devType: 该设备的设备类型，请参阅使用帮助章节。

>dataObjArr->objContent->devNum: 该设备的设备号

>dataObjArr->objContent->devSeq: 该设备的站点序号

>dataObjArr->objContent->protoVer: 该设备的软件版本

>dataObjArr->objContent->devSignGroup: 该设备的设备特征组

>dataObjArr->objContent->subCmdNum: 该设备的子命令数目

>dataObjArr->objContent->subCmdArr: 该设备的子命令数组，注：子命令数组中的内容，根据设备类型的不同会有所不同。

>**以下是终端对象-灯、灯带设备的子命令定义**

>dataObjArr->objContent->subCmdArr->subDevNum: 该灯设备的设备号

>dataObjArr->objContent->subCmdArr->powerOn: 该灯的开启状态 1=开启 0=关闭

>dataObjArr->objContent->subCmdArr->colorH/S/B: 该灯的HSB颜色


>**以下是终端对象-开关、插座、开关窗控制器、阀门机械臂设备的子命令定义**

>dataObjArr->objContent->subCmdArr->subDevNum: 该设备的设备号

>dataObjArr->objContent->subCmdArr->powerOn: 该设备的开启状态 1=开启 0=关闭

>**以下是终端对象-开关、插座、开关窗控制器、阀门机械臂设备的子命令定义**

>dataObjArr->objContent->subCmdArr->subDevNum: 该设备的设备号

>dataObjArr->objContent->subCmdArr->powerOn: 该设备的开启状态 1=开启 0=关闭


>**以下是终端对象-灯遥控器、开关贴设备的子命令定义**

>dataObjArr->objContent->subCmdArr->subDevNum: 该设备的设备号

>dataObjArr->objContent->subCmdArr->binding: 该设备的绑定状态 1=绑定 0=未绑定

>dataObjArr->objContent->subCmdArr->targetDevSubNum: 该设备所绑定的目标设备的设备号

>dataObjArr->objContent->subCmdArr->targetDevType: 该设备所绑定的目标设备的设备类型

>dataObjArr->objContent->subCmdArr->targetObjID: 该设备所绑定的目标设备的设备ID

>**以下是终端对象-告警类设备（门磁、红外、煤气、烟雾、浸水）的子命令定义**

>dataObjArr->objContent->subCmdArr->subDevNum: 该设备的设备号

>dataObjArr->objContent->subCmdArr->subCmd: 子命令码 为1

>dataObjArr->objContent->subCmdArr->securityReport: 告警模式 1=启用 0=未启用

>dataObjArr->objContent->subCmdArr->realReport: 实时通知模式 1=启用 0=未启用

>dataObjArr->objContent->subCmdArr->nightMode: 夜灯模式 1=启用 0=未启用

>dataObjArr->objContent->subCmdArr->lost: 失联 1=发生 0=未发生

>dataObjArr->objContent->subCmdArr->alarm1: 报警1 1=发生 0=未发生

>**以下是终端对象-流明检测设备的子命令定义**

>dataObjArr->objContent->subCmdArr->subDevNum: 该设备的设备号

>dataObjArr->objContent->subCmdArr->lumenReport: 流明上报

>dataObjArr->objContent->subCmdArr->lumens: 流明值

>**以下是终端对象-温湿度检测设备的子命令定义**

>dataObjArr->objContent->subCmdArr->subDevNum: 该设备的设备号

>dataObjArr->objContent->subCmdArr->tempReport: 温湿度上报

>dataObjArr->objContent->subCmdArr->humidity: 湿度值 0~100

>dataObjArr->objContent->subCmdArr->temperature: 温度值 -64~190度，+64度的偏移
