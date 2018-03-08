
### 简介说明
	Laravel5.5 + dingo + jwt + workerman 具有聊天的框架. 接口采用dingo标准的RESTFUL模式，JWT用于token验证，workerman用于聊天工具开发只要是websocket

## 克隆并安装扩展,简单的配置和操作

```
$ git clone git@github.com:sheldon9527/larvelworkerman.git
$ composer install
$ 设置 `storage` 目录必须让服务器有写入权限。
$ cp .env.example .env
$ vim .env
    DB_*
        填写数据库相关配置 your database configuration
    APP_KEY
        php artisan key:generate
    JWT_SECRET
        php artisan jwt:secret
```

## dingo+jwt 有用的阅读

读文档很重要，请先仔细读读文档 laravel, dingo/api，jwt，fractal 的文档。

- dingo/api [https://github.com/dingo/api](https://github.com/dingo/api)
- dingo api 中文文档 [dingo-api-wiki-zh](https://github.com/liyu001989/dingo-api-wiki-zh)
- jwt(json-web-token) [https://github.com/tymondesigns/jwt-auth](https://github.com/tymondesigns/jwt-auth)
- transformer [fractal](http://fractal.thephpleague.com/)
- apidoc 生成在线文档 [apidocjs](http://apidocjs.com/)
- rest api 参考规范 [jsonapi.org](http://jsonapi.org/format/)
- api 调试工具 [postman](https://www.getpostman.com/)
- 有用的文章 [http://oomusou.io/laravel/laravel-architecture](http://oomusou.io/laravel/laravel-architecture/)
- php lint [phplint](https://github.com/overtrue/phplint)
- Laravel 理念 [From Apprentice To Artisan](https://my.oschina.net/zgldh/blog/389246)


## dingo+jwt 操作步奏

```
$ php artisan migrate
$ php artisan db:seed (默认添加了10个用户)

头信息中可以增加 Accept:application/vnd.app.v1+json 切换v1和v2版本
```

## workerman 有用的阅读

- workerman手册  [http://doc.workerman.net/315110](http://doc.workerman.net/315110)
- GatewayWorker手册 [http://doc2.workerman.net/326102](http://doc2.workerman.net/326102)
- workerman官网 [http://www.workerman.net/](http://www.workerman.net/)
- workerman-chat [workerman-chat](http://www.workerman.net/workerman-chat)

## 特性
- 使用websocket协议
- 多浏览器支持（浏览器支持html5或者flash任意一种即可）
- 多房间支持
- 私聊支持
- 掉线自动重连
- 微博图片自动解析
- 聊天内容支持微博表情
- 支持多服务器部署
- 业务逻辑全部在一个文件中，快速入门可以参考这个文件[https://github.com/sheldon9527/larvelworkerman/blob/master/app/Chat/Events.php](https://github.com/sheldon9527/larvelworkerman/blob/master/app/Chat/Events.php)

## 启动停止(Linux系统)
### 以debug方式启动
- php start.php start

### 以daemon方式启动
- php start.php start -d

## 相关配置

- config/chat 连接服务的相关配置
- config/cache.php   stores.chat 是redis 的配置

## 说明

https://github.com/sheldon9527/larvelworkerman/blob/master/app/Chat/Events.php 这个文件是聊天的主要的业务逻辑文件，根据不同的业务需求进行编程。
