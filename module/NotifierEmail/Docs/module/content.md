基于邮件的消息通知

## 使用方式

集成邮箱通知后，在任意地方，通过如下方法调用，完成消息通知。

```
NotifierProvider::notify('<业务标识>','<通知标题>','<通知内容>')
```

业务标识可以为空，在任意模块，可以通过如下的方法调用注册业务标识。不同的业务标识可以配置不同的通知触达邮箱地址。

```php
NotifierBiz::registerQuick('CmsBook_NewBook', '新的客户咨询');
```


{ADMIN_MENUS}