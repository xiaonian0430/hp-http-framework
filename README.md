# hp-http-framework
一款高性能支持分布式部署的http框架。

## 服务器环境要求：

最低配置要求：
- 至少4G内存
- 至少40G磁盘
- 至少2v CPU

## 系统和软件要求

- centos v7.0 或更高版本
- php v7.0 或更高版本
    - 安装 event 扩展
    - 安装 pcntl 和 posix 扩展
    - 安装 redis 扩展

## 启动或停止(Linux系统)

```
#以debug方式启动
php run.php start -mode=produce

#以daemon方式启动
php run.php start -mode=produce -d

# 停止
php run.php stop -mode=produce
```
