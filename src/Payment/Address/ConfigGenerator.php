<?php

namespace Itxiao6\Wechat\Payment\Address;

use Itxiao6\Wechat\Bridge\Util;
use Itxiao6\Wechat\Bridge\Serializer;
use Itxiao6\Wechat\OAuth\AccessToken;

class ConfigGenerator
{
    /**
     * Thenbsp\Wechat\OAuth\AccessToken
     */
    protected $accessToken;

    /**
     * 构造方法
     */
    public function __construct(AccessToken $accessToken)
    {
        $this->setAccessToken($accessToken);
    }

    /**
     * 设置用户 AccessToken
     */
    public function setAccessToken(AccessToken $accessToken)
    {
        if( !$accessToken->isValid() ) {
            $accessToken->refresh();
        }

        $this->accessToken = $accessToken;
    }

    /**
     * 获取配置
     * @param bool $asArray
     * @param null $url
     * @return array|bool|float|int|string
     */
    public function getConfig($asArray = false,$url = null)
    {
        $options = array(
            'appid'         => $this->accessToken->getAppid(),
            'url'           => $url===null?Util::getCurrentUrl():$url,
            'timestamp'     => Util::getTimestamp(),
            'noncestr'      => Util::getRandomString(),
            'accesstoken'   => $this->accessToken['access_token']
        );

        // 按 ASCII 码排序
        ksort($options);

        $signature = http_build_query($options);
        $signature = urldecode($signature);
        $signature = sha1($signature);

        $config = array(
            'appId'     => $options['appid'],
            'scope'     => 'jsapi_address',
            'signType'  => 'sha1',
            'addrSign'  => $signature,
            'timeStamp' => $options['timestamp'],
            'nonceStr'  => $options['noncestr'],
        );

        return $asArray ? $config : Serializer::jsonEncode($config);
    }

    /**
     * 输出对象
     */
    public function __toString()
    {
        return $this->getConfig();
    }
}
