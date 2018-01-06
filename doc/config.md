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

>cmdCode=192 手机服务器登录

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

>objType=4 终端

>cmdCode=192 手机服务器登录

>objID=65535 读取所有终端设备的属性

>sliceSeq=1 分片序号，从1开始每次递增1，**只有需要分片读取的时候才添加该字段，初始读取不需要该字段；是否需要该字段由返回消息中的sliceM进行判断，参考返回消息中的说明**


data数据域定义：

无

**返回消息格式示例**：

> 注：1. 如果返回的数据域中携带sliceM=1,则说明需要进行分片发送，那么需要再次发送读取指令，同时在发送指令中增加sliceSeq=X，其中X为分片序号从1开始，每次递增。后续每次发送需要检测该属性。

> 2.如果返回的消息中携带sliceT = 1, 则说明分片数据已经结束，则不再需要发送分片读取指令。


*正确情况*

	$testConfigReadAllDevResponse = '{
		"msgLen":166,
    	"sign":50210,
    	"msgID":108,
    	"objType":4,
    	"cmdCode":192,
    	"objID":65535,
		"crc":45777,
    	"data":{
			"cmdRetCode":0,
    	    "objNum":3,
    	    "dataObj0Type":4,
    	    "dataObj0Reserved":0,
			"dataObj0ID":57244,
			"dataObj0FixLen":36,
			"dataObj0ExtLen":4,
			"dataObj0Fix":"00d0f600d013f7d50000011007f600030013000000000000000000000000001000000000",
			"dataObj0Ext":"0e000000",
			"dataObj1Type":4,
    	    "dataObj1Reserved":0,
			"dataObj1ID":2861,
			"dataObj1FixLen":36,
			"dataObj1ExtLen":4,
			"dataObj1Fix":"00d0f600d013d4ef0000011007f600030023000000000000000000000000101000000000",
			"dataObj1Ext":"0e000000",
			"dataObj2Type":4,
    	    "dataObj2Reserved":0,
			"dataObj2ID":36258,
			"dataObj2FixLen":36,
			"dataObj2ExtLen":4,
			"dataObj2Fix":"00d0f600d013f5150000011007f600030033000000000000000000000000201000000000",
			"dataObj2Ext":"0e020000",
			"sliceM":1,
			"sliceT":1,
			"crc":45777
    	}
	}';

data数据域定义：

>objNum: 此次返回的终端数量

>dataObj0Type：设备0类型

>dataObj0ID：设备0 ID

>dataObj0FixLen:设备0的固定域长度

>dataObj0ExtLen:设备0的扩展域长度

>dataObj0Fix: 设备0的固定域内容

>dataObj0Ext: 设备0的扩展域内容

>sliceM: 1 说明需要进行分片读取

>sliceT: 1 说明分片数据已经结束，不需要再进行分片读取操作。在正常的操作流程中，sliceM和sliceT不会同时出现。



