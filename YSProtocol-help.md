# 通信规约PHP SDK使用手册 #

## 通用描述

所有的功能调用都是使用 YSProtocol 类的静态方法encodeMsg和decodeMsg来完成。其中encodeMsg函数负责将json格式的消息转换为网关可识别的二进制格式；decodeMsg函数负责将网关发送的二进制数据转换为json格式。

## 函数定义

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

## JSON消息格式

### 通用JSON格式

所有传给YSProtocol的json消息必须满足以下的通用格式，也就是必须包含以下字段，否则将被转换函数抛弃，并返回错误日志。

