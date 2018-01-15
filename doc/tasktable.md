# 任务表操作命令

## 概述

任务表支持情景模式、闹钟、地址围栏。主要参数是任务触发条件、动作列表、场景列表和灯效列表。

目前默认四个任务表

>离家情景：45056

>回家情景：45057

>用餐情景：45058

>聚会情景：45059

命令码定义如下：

> 修改任务表：3

> 召唤任务表：16

## 指令定义

### 修改任务表
---

**描述**：

修改已存在任务表内容，需要传入需要修改的任务表ID。

**传入消息格式示例**：

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

>objType=11 任务表

>objID 所要修改的任务表ID

>cmdCode=3 修改任务表


data数据域定义：

>data->name: 任务表名称

>data->phone: 修改任务表人的手机号

>data->type: 任务类型 1=情景模式 2=闹钟 3=进入地址围栏 4=离开地址围栏

>data->devArr: 动作列表

>data->devArr->devID: 所要动作的设备的ID

>data->devArr->devSubCmdArr: 设备子命令数组。**具体子命令定义请参照设备遥控章节**

>data->staticSceneArr: 静态场景ID列表

>data->dynSceneArr: 动态场景ID列表

**返回消息格式示例**：

*正确情况*

	//正确情况
	$testConfigReadResponseJson = '{
	  "msgLen": 20,
	  "sign": 50210,
	  "msgID": 111,
	  "objType": 11,
	  "cmdCode": 3,
	  "objID": 45056,
	  "crc": 10725,
	  "data": {
	    "cmdRetCode": 0,
	    "crc": 10725
	  }
	}';

data数据域定义：

>cmdRetCode: 命令返回错误码 0为正确情况


### 召唤任务表
---

**描述**：

召唤任务表中定义的操作列表、静态场景和动态灯效

**传入消息格式示例**：

	$testStartTaskTable = '{
	    "sign":50210,
	    "msgID":112,
	    "objType":11,
	    "cmdCode":16,
	    "objID":45056,
	    "crc":255
	}';

>objType=11 任务表

>cmdCode=16 召唤任务表


data数据域定义：

无

**返回消息格式示例**：

*正确情况*

如上定义


