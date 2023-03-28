## 模块介绍

「一键分享」是一个提供网页生成一键分享功能


## 支持网站

- QQ（ `qq` ）
- 微博（ `weibo` ）
- 微信（ `wechat` ）
- QQ空间（ `qzone` ）
- Facebook（ `facebook` ）
- Google（ `google` ）
- Twitter（ `twitter` ）

## 使用方式

在 Blade 模板中通过如下方式调用

```
@include('module::ShareJS.View.buttons')
```

> 默认分享到 weibo,qq,qzone,wechat

或指定分享网站

```
@include('module::ShareJS.View.buttons',['sites'=>'weibo,qq'])
```

## 更多参考

- [https://github.com/overtrue/share.js](https://github.com/overtrue/share.js)