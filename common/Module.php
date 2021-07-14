<?php

namespace tencentyunoss\common;

use Exception;
use tpext\common\Module as baseModule;
use tpext\builder\common\Module as builderModule;

/**
 * 继承 Module 和 Resource都可以，考虑到以后的扩展性（比如提供OSS文件的管里等功能），使用Module
 * Undocumented class
 */
class Module  extends baseModule
{
    protected $version = '1.0.1';

    protected $name = 'builder.tencentyunoss';

    protected $title = '腾讯云OSS存储';

    protected $description = '支持腾讯云OSS存储文件上传功能';

    protected $root = __DIR__ . '/../';

    /**
     * 默认的configPath()是composer模式带`src`的，extend模式没有src所以重写一下。
     * 不重写此方法也可以，创建一个`src`目录把config.php放里面
     *
     * @return string
     */
    public function configPath()
    {
        return realpath($this->getRoot() . 'config.php');
    }

    public function loaded()
    {
        builderModule::getInstance()->addStorageDriver(OssStorage::class, '腾讯云OSS存储');
    }

    /**
     * Undocumented function
     *
     * @return boolean
     */
    public function install()
    {
        if (!class_exists('\\Qcloud\\Cos\\Client')) { //根据oss-sdk中某一个类是否存在来判断sdk是否已经安装
            $this->errors[] = new Exception('<p>请使用composer安装腾讯云OSS-sdk后再安装本扩展！</p><pre>composer require qcloud/cos-sdk-v5</pre>');
            return false;
        }

        return parent::install();
    }
}
