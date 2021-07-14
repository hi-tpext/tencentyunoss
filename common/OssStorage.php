<?php

namespace tencentyunoss\common;

use tpext\builder\inface\Storage;
use tpext\builder\common\model\Attachment;
use Qcloud\Cos\Client;
use UnexpectedValueException;

class OssStorage implements Storage
{
    /**
     * Undocumented function
     *
     * @param Attachment $attachment
     * @return string url
     */
    public function process($attachment)
    {
        $config = Module::getInstance()->getConfig();

        $secretId = $config['secret_id'];
        $secretKey = $config['secret_key'];
        $region = $config['region'];
        $bucketName = $config['bucket_name'];

        if (empty($secretId) || empty($secretKey) || empty($region) || empty($bucketName)) {
            trace('腾讯云cos配置有误');
            $this->delete($attachment);
            return '';
        }

        try {
            $bucketExists = false;

            $cosClient = new Client([
                'region' => $region,
                'credentials' => [
                    'secretId' => $secretId,
                    'secretKey' => $secretKey
                ]
            ]);

            $result = $cosClient->listBuckets();

            $bucketList = $result && isset($result['Buckets']) ? $result['Buckets'] : [];

            foreach ($bucketList as $bu) {
                if ($bu && isset($bu['Bucket']) && $bu['Bucket']['Name'] == $bucketName) {
                    $bucketExists = true;
                    break;
                }
            }

            if (!$bucketExists) { //存储桶不存在，创建
                $cosClient->createBucket(['Bucket' => $bucketName]);
            }
        } catch (\Throwable $e) {
            trace('腾讯云cosClient初始化失败');
            trace($e->__toString());
            $this->delete($attachment);
            return '';
        }

        try {
            $name = preg_replace('/^.+?\/([^\/]+)$/', '$1', $attachment['url']); //获取带后缀的文件名
            $res = $cosClient->putObject([
                'Bucket' => $bucketName,
                'Key' => $name,
                'Body' => fopen('.' . $attachment['url'], 'rb'),
                'ACL' => 'public-read'
            ]);

            if ($res && ($res = $res->toArray()) && isset($res['Location'])) {
                $ossUrl = $res['Location'] . (strpos($res['Location'], '?') ? '&id=' : '?id=') . $attachment['id'];
                $ossUrl = '//' . preg_replace('/^https?:\/\//', '', $ossUrl); //去掉http协议头，以//开头
                $attachment['url'] = $ossUrl;
                $attachment->save();
                return $ossUrl;
            } else {
                throw new UnexpectedValueException('未知错误');
            }
        } catch (\Throwable $e) {
            trace('腾讯云Oss文件上传失败');
            trace($e->__toString());
            $this->delete($attachment);
            return '';
        }

        return '';
    }

    /**
     * OSS操作失败，删除数据库已保存的记录和已上传的文件
     *
     * @param Attachment $attachment
     * @return boolean
     */
    private function delete($attachment)
    {
        $res1 = Attachment::where('id', $attachment['id'])->delete();
        $res2 = @unlink('.' . $attachment['url']);

        return $res1 && $res2;
    }
}
