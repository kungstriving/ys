# SDK-PHP使用说明

## 1. 通用描述

所有的功能调用都是使用 Third_Ys_Sdk 类的静态方法encodeMsg和decodeMsg来完成。其中encodeMsg函数负责将json格式的消息转换为网关可识别的二进制格式；decodeMsg函数负责将网关发送的二进制数据转换为json格式。

代码示例如下：

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
    	}
	}';

	//objType = 0 //关
	//cmdCode = 1 //手机局域网登录

	$testBin = YSProtocol::encodeMsg($testLoginLanJson);

	//send $testBin to gate
	
	...

	//get response from gate
	$loginResult = getFromGate();

	$decodedJson = YSProtocol::decodeMsg($loginResult);

	echo json_decode($decodedJson)->data->username;

**几个限制条件**

由于网关报文收发速度慢，所以任何设备发往网关的报文限速3包/秒

目前最多支持32个设备

发送的报文可能进行分片发送，对于文件传输和手机广播命令的情况下可能出现报文分片操作。

## 2. 函数定义

### YSProtocol::encodeMsg

**概述**

版本 1.0

YSProtocol::encodeMsg - 负责将json格式的消息转换为网关可识别的二进制格式。

**定义**

```
string YSProtocol::encodeMsg(string $msgJson)
```

**参数**

**msgJson**
>传入的json消息格式，具体格式请参照下面的**JSON消息格式**章节

**返回值**

返回二进制数据的字符串表示，可以直接发送给网关。

### YSProtocol::decodeMsg

**概述**

版本 1.0

YSProtocol::decodeMsg - 负责将网关返回的二进制格式数据转换为json格式的消息。

**定义**

```
string YSProtocol::decodeMsg(string $msgBin)
```

**参数**

**msgBin**
>传入的二进制格式网关消息，直接将网关返回的数据传入

**返回值**

返回解析后的json格式，具体格式定义请参照**JSON消息格式**章节

## 3. JSON消息格式

### 通用JSON格式

所有传入和传出YSProtocol的json消息必须满足以下的通用格式，也就是必须包含以下字段，否则将被转换函数抛弃，并返回错误日志。

    {
        "sign":1234,
        "msgID":101,
        "objType":0,
        "cmdCode":1,
        "objID":0,
        "data":{
			...
        }
    }

属性定义如下

**sign** : 报文特征码。登录智能网关的报文填0，在登录智能网关成功后，由网关返回给手机特征码，以后手机发包需要带上这个特征码，特征码不符的报文将丢弃，也可用于多台手机检查是否是发给自己的报文。

**msgID** : 报文序号。主动发送方随机产生，应答方在响应的时候，将此序号返回，用于检验不同报文的响应。注意随机产生序号的时候，必须跳过65278和32510两个特殊值。

**objType** : 操作对象类型。对应关系如下：

    0-网关
	4-终端设备
	5-组
	7-静态场景
	8-动态场景
	9-闹钟
	10-地址围栏
	11-任务

对哪种对象进行操作就设置为哪个数字。

**cmdCode** : 命令码，即针对各种类型的操作对象不同的指令。请参照各模块的命令码定义。

**objID** : 具体要操作对象的ID，比如某个灯的ID。

**data** ： 数据域，不同类型对象的不同操作会对应不同的数据域。请参照具体各个操作章节。

### 网关相关指令定义

#### 手机通过局域网登录

---

**描述**：

如果登录成功，则由网关分配特征码，并返回“网关基本属性”的数据内容。

**传入消息格式示例**：

	$testLoginLanJson = '{
    	"sign":0,
    	"msgID":101,
    	"objType":0,
    	"cmdCode":1,
    	"objID":0,
    	"data":{
    	    "username":"test",
    	    "phoneNum":"13366666666",
    	    "gateID":"00d0f60020bc1356"
    	}
	}';

>objType=0 网关

>cmdCode=1 手机局域网登录


data数据域定义：
>username：用户名

>phoneNum：用户手机号

>gateID：要登录网关的标识


**返回消息格式示例**：

*正确情况*

	//正确情况
	$testLoginLanResponseJson = '{
    	"sign":1234,
    	"msgID":101,
    	"objType":0,
    	"cmdCode":1,
    	"objID":0,
    	"data":{
			"cmdRetCode":0,
    	    "username":"test",
    	    "phoneNum":"13366666666",
    	    "gateID":"00d0f60020bc1356",
			"sign":1234,
			"gateFixLen":128,
			"gateExtLen":128,
			"protoVer":1,
			"gateID2":"87654321",
			"gateMAC":"0000000000000",
			"gateName":"",
			"hbLan":300,
			"hbWLan":300,
			"netData":"",
			"crc":255
    	}
	}';

data数据域定义：

>cmdRetCode: 命令返回错误码 0为正确情况

>gateFixLen：网关固定域长度

>gateExtLen：网关扩展域长度

>protoVer:协议版本号

>hbLan:局域网心跳周期，默认300秒

>hbWLan:公网心跳周期，默认300秒

>netData:网络数据

*出错情况*

	//出错情况
	$testLoginLanErrorResponseJson = '{
    	"sign":1234,
    	"msgID":101,
    	"objType":0,
    	"cmdCode":1,
    	"objID":0,
    	"data":{
			"cmdRetCode":1,
    	    "username":"test",
    	    "phoneNum":"13366666666",
    	    "gateID":"00d0f60020bc1356",
			"crc":255
    	}
	}';

#### 手机通过服务器登录

---

**描述**：

如果登录成功，则由网关分配特征码，并返回“网关基本属性”的数据内容。如果是手机初次登录，则需要提示用户按压网关按钮，此时网关会主动向手机和服务器发送允许手机初次登录的消息，在手机收到该消息后，主动发起重新登录的操作，此时便可以新加入到网关中。

**传入消息格式示例**：

	$testLoginServerJson = '{
    	"sign":0,
    	"msgID":101,
    	"objType":0,
    	"cmdCode":2,
    	"objID":0,
    	"data":{
    	    "username":"test",
    	    "phoneNum":"13366666666",
    	    "gateID":"00d0f60020bc1356"
    	},
		"crc":255
	}';

>objType=0 网关

>cmdCode=2 手机服务器登录


data数据域定义：

>username：用户名

>phoneNum：用户手机号

>gateID：要登录网关的标识，通过*服务器识别网关*指令获取


**返回消息格式示例**：

*正确情况*

	//正确情况
	$testLoginLanResponseJson = '{
    	"sign":1234,
    	"msgID":101,
    	"objType":0,
    	"cmdCode":2,
    	"objID":0,
    	"data":{
			"cmdRetCode":0,
    	    "username":"test",
    	    "phoneNum":"13366666666",
    	    "gateID":"00d0f60020bc1356",
			"sign":14716,
			"gateFixLen":128,
			"gateExtLen":128,
			"protoVer":1,
			"gateID2":"00d0f60020bc1356",
			"gateMAC":"cafc32fb4ba1",
			"gateName":"",
			"hbLan":300,
			"hbWLan":300,
			"netData":"",
			"crc":255
    	}
	}';

data数据域定义：

>cmdRetCode: 命令返回错误码 0为正确情况

>gateFixLen：网关固定域长度

>gateExtLen：网关扩展域长度

>protoVer:协议版本号

>hbLan:局域网心跳周期，默认300秒

>hbWLan:公网心跳周期，默认300秒

>netData:网络数据

>gateID:要登录的网关设备标识

>gateID2:返回的网关设备标识


*出错情况*

	//出错情况
	$testLoginLanErrorResponseJson = '{
    	"sign":1234,
    	"msgID":101,
    	"objType":0,
    	"cmdCode":2,
    	"objID":0,
    	"data":{
			"cmdRetCode":1,
    	    "username":"test",
    	    "phoneNum":"13366666666",
    	    "gateID":"87654321",
			"crc":255
    	}
	}';

#### 手机退出登录

---

**描述**：

告知网关该手机退出登录，则后续使用之前特征码的操作将不被允许，需要重新登录获取新的特征码。

**传入消息格式示例**：

	$testLoginOutJson = '{
    	"sign":1234,
    	"msgID":102,
    	"objType":0,
    	"cmdCode":3,
    	"objID":0,
		"crc":255
	}';

>objType=0 网关

>cmdCode=2 手机服务器登录


data数据域定义：



**返回消息格式示例**：

*正确情况*

	//正确情况
	$testLoginOutResponseJson = '{
    	"sign":14716,
    	"msgID":102,
    	"objType":0,
    	"cmdCode":3,
    	"objID":0,
    	"data":{
			"cmdRetCode":0,
			"crc":41152
    	},
		"crc":41152
	}';

data数据域定义：


#### 手机心跳

---

**描述**：

手机在访问时，必须保证每5分钟至少一个心跳报文，否则会话会断掉，就需要手机重新登录获取新的特征码。

具备对钟功能，手机登录成功后，至少发出一次心跳保证对钟功能。


**传入消息格式示例**：

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

>objType=0 网关

>cmdCode=4 手机心跳

data数据域定义：

>phoneNum:发起心跳的用户手机号码

>year/month/day/hour/minute/second/weekday: 对钟使用的时间设置

>hb：新设置的心跳间隔，默认300秒

**返回消息格式示例**：

*正确情况*

	//正确情况
	$testLoginLanResponseJson = '{
    	"sign":1234,
    	"msgID":103,
    	"objType":0,
    	"cmdCode":4,
    	"objID":0,
    	"data":{
			"cmdRetCode":0,
			"crc":255
    	}
	}';

data数据域定义：

#### 服务器识别网关

---

**描述**：

当网关建立TCP连接到远程服务器之后，服务器主动发出识别网关命令，可以获取网关的版本和设备标识信息，用于支持以后用户手机app的远程操作。

此报文不需要特征码。


**传入消息格式示例**：

	$testServerIdentifyGateJson = '{
    	"sign":0,
    	"msgID":103,
    	"objType":0,
    	"cmdCode":5,
    	"objID":0,
		"data":{
	        "serverID":"YS-PHP-Server"
    	},
		"crc":255
	}';

>sign=0 此命令中特征码为0

>objType=0 网关

>cmdCode=5 服务器识别网关

data数据域定义：

>serverID:唯一标识服务器的字符，不超过16字节

**返回消息格式示例**：

*正确情况*

	//正确情况
	$$testServerIdentifyResponseJson = '{
		"msgLen":178,
    	"sign":0,
    	"msgID":103,
    	"objType":0,
    	"cmdCode":5,
    	"objID":0,
		"crc":57344,
    	"data":{
			"cmdRetCode":0,
			"serverID":"YS-PHP-Server",
			"sign":0,
			"gateFixLen":136,
			"gateExtLen":0,
			"protoVer":769,
			"gateID":"00d0f60020bc1355",
			"gateMAC":"cafc32fb4ba1",
			"gateName":"",
			"hbLan":300,
			"hbWLan":300,
			"netData":"",
			"crc":57344
    	}
	}';

data数据域定义：

>cmdRetCode: 命令返回错误码 0为正确情况

>serverID:服务器唯一标识
>
>gateFixLen：网关固定域长度

>gateExtLen：网关扩展域长度

>protoVer:协议版本号
>
>gateID:网关设备标识

>gateMAC:网关MAC

>gateName:网关名称

>hbLan:局域网心跳周期，默认300秒

>hbWLan:公网心跳周期，默认300秒

>netData:网络数据

#### 服务器心跳网关

---

**描述**：

服务器和网关之间默认必须保证5分钟内至少有一个报文。

注意，服务器识别到网关之后，由服务器主动发出心跳报文维持连接。并且至少发出一次心跳保证对钟功能。

具备对钟功能。服务器在线的情况下，其时钟优先级最高，网关以服务器的时钟为准，不再跟随手机的时钟


此报文不需要特征码。


**传入消息格式示例**：

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

>sign=0 此命令中特征码为0

>objType=0 网关

>cmdCode=6 服务器心跳网关

data数据域定义：

>serverID:唯一标识服务器的字符，不超过16字节

>year/month/day/hour/minute/second/weekday: 对钟使用的时间设置


**返回消息格式示例**：

*正确情况*

	//正确情况
	$$testServerIdentifyResponseJson = '{
		"msgLen":20,
		"sign":0,
    	"msgID":10,
    	"objType":0,
    	"cmdCode":6,
    	"objID":0,
    	"data":{
			"cmdRetCode":0,
			"crc":255
    	}
	}';

data数据域定义：

>cmdRetCode: 命令返回错误码 0为正确情况


#### 搜索新设备

---

**描述**：

此命令用于网关发现新设备，搜索持续时间携带在命令中，网关收到命令后，在持续时间内（默认60秒）允许新设备的加入，建议在（持续时间+5秒）之后，手机调用“列表所有终端”的命令，以判断新设备的加入。


**传入消息格式示例**：

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

>sign= 手机登录后获取的特征码

>objType=0 网关

>cmdCode=10 搜索新设备

data数据域定义：

>lastSecs:搜索新设备持续时间，单位秒



**返回消息格式示例**：

*正确情况*

	//正确情况
	$$testServerIdentifyResponseJson = '{
		"msgLen":20,
		"sign":0,
    	"msgID":10,
    	"objType":0,
    	"cmdCode":6,
    	"objID":0,
    	"data":{
			"cmdRetCode":0,
			"crc":255
    	}
	}';

data数据域定义：

>cmdRetCode: 命令返回错误码 0为正确情况

#### 列表所有终端

---

**描述**：

此命令用于获取网关下所有终端设备的ID和设备标识的对应关系


**传入消息格式示例**：

	$testListAllDevsJson = '{
	    "sign":50106,
	    "msgID":105,
	    "objType":0,
	    "cmdCode":11,
	    "objID":0,
	    "crc":255
	}';

>sign= 手机登录后获取的特征码

>objType=0 网关

>cmdCode=11 搜索新设备

data数据域定义：


**返回消息格式示例**：

*正确情况*

	//正确情况
	$testServerIdentifyResponseJson = '{
		"msgLen":58,
		"sign":50106,
		"msgID":105,
		"objType":0,
		"cmdCode":11,
		"objID":0,
		"crc":52658,
		"data"{
			"dev0ID":57244,
			"dev0Type":4097,
			"dev0MAC":"00d0f600d013f7d5",
			"dev1ID":2861,
			"dev1Type":4097,
			"dev1MAC":"00d0f600d013d4ef",
			"dev2ID":36258,
			"dev2Type":4097,
			"dev2MAC":"00d0f600d013f515",
			"crc":52658,
			"devNum":3
		}
	}';

data数据域定义：

>dev0ID: 站点ID是智能家居网络内部设备节点的ID，网内唯一。注：其中的数字0，会随着设备数量递增。

>dev0Type:站点类别

>dev0MAC:站点MAC，全球唯一

>devNum:站点总个数

### 全局相关指令

#### 列表同类对象所有ID

---

**描述**：

列表相同类型的数据对象的ID，仅仅列表对象的ID。支持终端类型（0x04）、组类型（0x05）、静态场景类型（0x07）、动态场景（0x08）、任务表（0x0B)


**传入消息格式示例**：

	$testListObjID = '{
	    "sign":50106,
	    "msgID":110,
	    "objType":4,
	    "cmdCode":195,
	    "objID":65535,
	    "crc":255
	}';

>sign= 手机登录后获取的特征码

>objType=4 终端；具体对象类型参照文档开头的对象类型定义

>cmdCode=195 列表所有同类对象ID

>objID=65535 默认，无需修改

data数据域定义：


**返回消息格式示例**：

*正确情况*

	//正确情况
	$testListObjIDResponse = '{
		"msgLen": 30,
		"sign": 50210,
		"msgID": 110,
		"objType": 4,
		"cmdCode": 195,
		"objID": 65535,
		"crc": 15565,
		"data": {
			"obj0ID": 45056,
    		"obj1ID": 45057,
    		"obj2ID": 45058,
    		"obj3ID": 45059,
    		"crc": 15565,
    		"objNum": 4
		}
	}';

data数据域定义：

>obj0ID: 对象ID。注：其中的数字0，会随着设备数量递增。

>objNum:站点总个数
