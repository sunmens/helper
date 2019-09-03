# 常用的一些扩展类库

> 更新完善中

> 以下类库都在`\\sunmens\\helper`命名空间下

## Encrypt
> 加密解密

```
// 加密
Encrypt::Encrypt();

// 解密
Encrypt::Decrypt();
```

## Time
> 时间戳操作

```
// 今日开始和结束的时间戳
Time::today();

// 昨日开始和结束的时间戳
Time::yesterday();

// 明天开始和结束的时间戳
Time::tomorrow();

// 某一天开始和结束的时间戳
Time::day($t);

// 本周开始和结束的时间戳
Time::week();

// 上周开始和结束的时间戳
Time::lastWeek();

// 本月开始和结束的时间戳
Time::month();

// 上月开始和结束的时间戳
Time::lastMonth();

// 今年开始和结束的时间戳
Time::year();

// 去年开始和结束的时间戳
Time::lastYear();

// 获取7天前零点到现在的时间戳
Time::dayToNow(7)

// 获取7天前零点到昨日结束的时间戳
Time::dayToNow(7, true)

// 获取7天前的时间戳
Time::daysAgo(7)

//  获取7天后的时间戳
Time::daysAfter(7)

// 天数转换成秒数
Time::daysToSecond(5)

// 周数转换成秒数
Time::weekToSecond(5)

```