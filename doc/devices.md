# 终端设备操作命令

## 概述

终端设备（色灯、灯带、触摸开关、墙壁插座等）操作，可以支持对单个、多个以及广播设备下发指令。操作命令的返回码只表示正确接收到数据，并不代表真实的操作结果。


## 指令定义

### 终端站点遥控

遥控操作用于对站点进行子命令操作，比如点灯、调色

#### 单个灯设备遥控命令
---

**描述**：

对单个灯进行遥控命令

**传入消息格式示例**：

	$testLightControl ='{
	    "sign":50210,
	    "msgID":111,
	    "objType":4,
	    "cmdCode":16,
	    "objID":36258,
	    "data":{
	        "subCmdNum":1,
	        "subCmdArr":[
	            {"devType":16,"subDevNum":1,"powerOn":1,"colorH":32,"colorS":32,"colorB":128}
	        ]
	    },
	    "crc":255
	}';

>objType=4 终端设备

>cmdCode=16 站点遥控

>objID 所要控制的设备ID


data数据域定义：

>subCmdNum 子命令个数。一次可以发送多个子命令，比如触摸开关，一次可以对触摸开关所关联的三个灯进行操作命令。

>subCmdArr 子命令数组

>subCmdArr->devType 设备类型 16=灯
>
>subCmdArr->subDevNum 子设备号。目前灯只有一个子设备，所以默认填1.

>subCmdArr->powerOn 开关。1=开，0=关

>subCmdArr->colorH/colorS/colorB 色灯的HSB值


**返回消息格式示例**：

*正确情况*

	//正确情况
	$testLightControlResponse = '{
		"msgLen": 20,
  		"sign": 50210,
  		"msgID": 111,
  		"objType": 4,
  		"cmdCode": 16,
  		"objID": 36258,
  		"crc": 49405,
  		"data": {
    		"objID": 36258,
    		"cmdRetCode": 0,
    		"crc": 49405
  		}
	}';

data数据域定义：

>cmdRetCode: 命令返回错误码 0为正确情况

#### 单个墙壁开关遥控命令
---

**描述**：

对单个墙壁开关进行遥控命令

**传入消息格式示例**：

	$testLightControl ='{
	    "sign":50210,
	    "msgID":112,
	    "objType":4,
	    "cmdCode":16,
	    "objID":45539,
	    "data":{
	        "subCmdNum":1,
	        "subCmdArr":[
	            {"devType":33,"subDevNum":1,"powerOn":1}
	        ]
	    },
	    "crc":255
	}';

>objType=4 终端设备

>cmdCode=16 站点遥控

>objID 所要控制的设备ID


data数据域定义：

>subCmdNum 子命令个数。一次可以发送多个子命令，比如触摸开关，一次可以对触摸开关所关联的三个灯进行操作命令。

>subCmdArr 子命令数组

>subCmdArr->devType 设备类型 33=零火线多路触摸开关 32=单火线多路触摸开关
>
>subCmdArr->subDevNum 子设备号。0=控制全部开关键 1/2/3分别指第1/2/3个开关键

>subCmdArr->powerOn 开关。1=开，0=关


**返回消息格式示例**：

*正确情况*

	//正确情况
	$testLightControlResponse = '{
		"msgLen": 20,
  		"sign": 50210,
  		"msgID": 111,
  		"objType": 4,
  		"cmdCode": 16,
  		"objID": 36258,
  		"crc": 49405,
  		"data": {
    		"objID": 36258,
    		"cmdRetCode": 0,
    		"crc": 49405
  		}
	}';

data数据域定义：

>cmdRetCode: 命令返回错误码 0为正确情况

#### 灯遥控器遥控命令
---

**描述**：

对灯遥控器进行遥控命令

**传入消息格式示例**：

	$testLightControl ='{
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

>objType=4 终端设备

>cmdCode=16 站点遥控

>objID 所要控制的设备ID


data数据域定义：

>subCmdNum 子命令个数。一次可以发送多个子命令，比如触摸开关，一次可以对触摸开关所关联的三个灯进行操作命令。

>subCmdArr 子命令数组

>subCmdArr->devType 设备类型 48=灯遥控器
>
>subCmdArr->subDevNum 子设备号。1/2/3分别指第1/2/3组按键

>subCmdArr->binding 绑定。1=绑定，0=解绑定

>subCmdArr->targetID 所要绑定或解绑定的对象ID

>subCmdArr->targetSubNum 所要绑定或解绑定的对象的子设备号


**返回消息格式示例**：

*正确情况*

	//正确情况
	$testLightControlResponse = '{
		"msgLen": 20,
  		"sign": 50210,
  		"msgID": 111,
  		"objType": 4,
  		"cmdCode": 16,
  		"objID": 36258,
  		"crc": 49405,
  		"data": {
    		"objID": 36258,
    		"cmdRetCode": 0,
    		"crc": 49405
  		}
	}';

data数据域定义：

>cmdRetCode: 命令返回错误码 0为正确情况


#### 开关贴遥控命令
---

**描述**：

对开关贴进行遥控命令

**传入消息格式示例**：

	$testLightControl ='{
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

>objType=4 终端设备

>cmdCode=16 站点遥控

>objID 所要控制的设备ID


data数据域定义：

>subCmdNum 子命令个数。一次可以发送多个子命令，比如触摸开关，一次可以对触摸开关所关联的三个灯进行操作命令。

>subCmdArr 子命令数组

>subCmdArr->devType 设备类型 52=开关贴
>
>subCmdArr->subDevNum 子设备号。1/-6分别指第1-6按钮

>subCmdArr->binding 绑定。1=绑定，0=解绑定

>subCmdArr->targetID 所要绑定或解绑定的对象ID

>subCmdArr->targetSubNum 所要绑定或解绑定的对象的子设备号


**返回消息格式示例**：

*正确情况*

	//正确情况
	$testLightControlResponse = '{
		"msgLen": 20,
  		"sign": 50210,
  		"msgID": 111,
  		"objType": 4,
  		"cmdCode": 16,
  		"objID": 36258,
  		"crc": 49405,
  		"data": {
    		"objID": 36258,
    		"cmdRetCode": 0,
    		"crc": 49405
  		}
	}';

data数据域定义：

>cmdRetCode: 命令返回错误码 0为正确情况

### 终端站点状态上报与告警变化

终端站点主动上报状态变化与告警变化。

该命令手机或网关收到后无需应答。报文特征码为65535.

#### 主动上报命令解析
---


**返回消息格式示例**：

*正确情况*

	$testStatusUploadResponse = '{
	  "msgLen": 50,
	  "sign": 65535,
	  "msgID": 1,
	  "objType": 4,
	  "cmdCode": 32,
	  "objID": 65534,
	  "crc": 415,
	  "data": {
	    "devNum": 3,
	    "devCmdArr": [
	      {
	        "devID": 57244,
	        "devType": 16,
	        "devSubCmdNum": 1,
	        "devSubCmdArr": [
	          {
	            "subDevNum": 1,
	            "powerOn": 1,
	            "colorH": 0,
	            "colorS": 254,
	            "colorB": 54
	          }
	        ]
	      },
	      {
	        "devID": 2861,
	        "devType": 16,
	        "devSubCmdNum": 1,
	        "devSubCmdArr": [
	          {
	            "subDevNum": 1,
	            "powerOn": 1,
	            "colorH": 0,
	            "colorS": 254,
	            "colorB": 54
	          }
	        ]
	      },
	      {
	        "devID": 36258,
	        "devType": 16,
	        "devSubCmdNum": 1,
	        "devSubCmdArr": [
	          {
	            "subDevNum": 1,
	            "powerOn": 1,
	            "colorH": 0,
	            "colorS": 254,
	            "colorB": 54
	          }
	        ]
	      }
	    ],
	    "crc": 415 
	  }
	}';

data数据域定义：

>devNum: 上报状态的设备个数

>devCmdArr: 设备状态数组

>devCmdArr->devID: 设备ID

>devCmdArr->devType: 设备类型

>devCmdArr->devSubCmdNum: 子命令个数

>devCmdArr->devSubCmdArr: 子命令数组

>devCmdArr->devSubCmdArr-> : 子命令的内容请参考终端遥控章节中每个终端的定义，遥控命令与返回的状态格式一致。
