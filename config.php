<?php

return [
    'secret_id' => '',
    'secret_key' => '',
    'region' => 'ap-chengdu',
    'bucket_name' => '',
    //
    //配置描述
    '__config__' => [
        'secret_id' => ['type' => 'text', 'label' => 'SecretId', 'size' => [2, 8], 'help' => '云API密钥SecretId'],
        'secret_key' => ['type' => 'text', 'label' => 'SecretKey', 'size' => [2, 8], 'help' => '云API密钥SecretKey'],
        'region' => ['type' => 'text', 'label' => 'Region', 'size' => [2, 8], 'help' => '存储桶地域，例如：ap-chengdu'],
        'bucket_name' => ['type' => 'text', 'label' => 'BucketName', 'size' => [2, 8], 'help' => '存储桶名字，如：tpext-123456789，123456789为腾讯云账户APPID'],
    ],
];
