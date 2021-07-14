# Aliyun-OSS

## Tpextbuilder的腾讯云-OSS驱动

### 请使用composer安装**腾讯云COS-PHP-SDK-V5(XML API)**后再安装本扩展

```bash
composer require qcloud/cos-sdk-v5
```

### 注意事项

- SecretId/SecretKey可在腾讯云API密钥管理页面获取

- 存储桶的命名，格式：BucketName-APPID。

    如`tpext-123456789.cos.ap-chengdu.myqcloud.com`中`tpext-123456789`是存储桶名，`123456789`是APPID(腾讯云APPID)，`ap-chengdu`存储桶地域。

    APPID 是腾讯云账户的账户标识之一，用于关联云资源。在用户成功申请腾讯云账户后，系统自动为用户分配一个 APPID。您可登录腾讯云控制台， 在 账号信息 查看 APPID。

- 你可以事先在腾讯云控制台创建好存储桶，然后填入配置里面。

- 存储桶限有三种[私有读写/公有读私有写/公有读写]，建议设置为：**公有读私有写**。

- 填入的存储桶地域要和存储桶匹配，比如你在[成都ap-chengdu]创建的存储桶，存储桶地域不能填[北京ap-beijing]。

- 如果没有事先创建，会按填写的[存储桶名称]和[存储桶地域]和[存储权公有读私有写]自动创建一个。

- 可选的存储桶地域：

    <https://cloud.tencent.com/document/product/436/6224>

### 使用

1. 全局设置

    在扩展`tpext ui生成(tpext.builder)`的配置里面选择本驱动：[腾讯云OSS存储]，保存，设置后所有的文件上传都使用腾讯云OSS。

2. 单独使用
    ckeditor,mdeditor,tinymce,ueditor,file,image,multipleFile,multipleImage等可使用`storageDriver($class)`方法单独设置。

```php

$form->image('logo', '封面图')->mediumSize()->storageDriver(\tencentyunoss\common\OssStorage::class);//使用腾讯云oss存储

$form->file('file', '附件')->mediumSize()->storageDriver(\tpext\builder\logic\LocalStorage::class);//服务器本地存储
```
