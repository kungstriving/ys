# 通信规约PHP SDK使用手册 #

## 1. 通用描述

所有的功能调用都是使用 YSProtocol 类的静态方法encodeMsg和decodeMsg来完成。其中encodeMsg函数负责将json格式的消息转换为网关可识别的二进制格式；decodeMsg函数负责将网关发送的二进制数据转换为json格式。

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


## 2. 函数定义

### YSProtocol::encodeMsg

#### 概述
版本 1.0
YSProtocol::encodeMsg - 负责将json格式的消息转换为网关可识别的二进制格式。

#### 定义

```
string YSProtocol::encodeMsg(string $msgJson)
```

#### 参数

**msgJson**
>传入的json消息格式，具体格式请参照下面的**JSON消息格式**章节

#### 返回值

返回二进制数据的字符串表示，可以直接发送给网关。

### YSProtocol::decodeMsg

#### 概述
版本 1.0
YSProtocol::decodeMsg - 负责将网关返回的二进制格式数据转换为json格式的消息。

#### 定义

```
string YSProtocol::decodeMsg(string $msgBin)
```

#### 参数

**msgBin**
>传入的二进制格式网关消息，直接将网关返回的数据传入

#### 返回值

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

**cmdCode** : 命令码，即针对各种类型的操作对象不同的指令。请参照命令码章节。

**objID** : 具体要操作对象的ID，比如某个灯的ID。

**data** ： 数据域，不同类型对象的不同操作会对应不同的数据域。请参照具体各个操作章节。

### 网关消息定义

#### 手机通过局域网登录

---

传入消息格式示例：

	$testLoginLanJson = '{
    	"sign":0,
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

data数据域定义：
>username：用户名

>phoneNum：用户手机号

>gateID：要登录网关的标识


返回消息格式示例：

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
    	    "gateID":"987654321",
			"sign":1234,
			"gateFixLen":128,
			"gateExtLen":128,
			"protoVer":1,
			"gateID2":"87654321",
			"gateMAC":"000000",
			"gateName":"",
			"hbLan":300,
			"hbWLan":300,
			"crc":255
    	}
	}';

data数据域定义：

>cmdRetCode: 命令返回错误码 0为正确情况

>sign:由网关分配的特征码，后续请求都应该携带该特征码

>gateFixLen：网关固定域长度

>gateExtLen：网关扩展域长度

>protoVer:协议版本号

>hbLan:局域网心跳周期，默认300秒

>hbWLan:公网心跳周期，默认300秒

#### 手机通过服务器登录

---

传入消息格式示例：

	$testLoginServerJson = '{
    	"sign":0,
    	"msgID":101,
    	"objType":0,
    	"cmdCode":2,
    	"objID":0,
    	"data":{
    	    "username":"test",
    	    "phoneNum":"13366666666",
    	    "gateID":"87654321"
    	}
	}';

data数据域定义：
>username：用户名

>phoneNum：用户手机号

>gateID：要登录网关的标识


返回消息格式示例：

	//正确情况
	$testLoginServerResponseJson = '{
    	"sign":1234,
    	"msgID":101,
    	"objType":0,
    	"cmdCode":1,
    	"objID":0,
    	"data":{
			"cmdRetCode":0,
    	    "username":"test",
    	    "phoneNum":"13366666666",
    	    "gateID":"987654321",
			"sign":1234,
			"gateFixLen":128,
			"gateExtLen":128,
			"protoVer":1,
			"gateID2":"87654321",
			"gateMAC":"000000",
			"gateName":"",
			"hbLan":300,
			"hbWLan":300,
			"crc":255
    	}
	}';

data数据域定义：

>cmdRetCode: 命令返回错误码 0为正确情况

>sign:由网关分配的特征码

>gateFixLen：网关固定域长度

>gateExtLen：网关扩展域长度

>protoVer:协议版本号

>hbLan:局域网心跳周期，默认300秒

>hbWLan:公网心跳周期，默认300秒


