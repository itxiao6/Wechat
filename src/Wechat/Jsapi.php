<?php

namespace Itxiao6\Wechat\Wechat;

use Itxiao6\Wechat\Bridge\Util;
use Itxiao6\Wechat\Bridge\CacheTrait;
use Itxiao6\Wechat\Bridge\Serializer;
use Itxiao6\Wechat\Wechat\Jsapi\Ticket;
use Itxiao6\Wechat\Wechat\AccessToken;

class Jsapi
{
    /**
     * Cache Trait
     */
    use CacheTrait;

    /**
     * Thenbsp\Wechat\Wechat\AccessToken
     */
    protected $accessToken;

    /**
     * 是否开起调试
     */
    protected $debug = false;

    /**
     * 接口列表
     */
    protected $api = array();

    /**
     * 全部接口
     */
    protected $apiValids = array(
        'onMenuShareTimeline', 'onMenuShareAppMessage', 'onMenuShareQQ', 'onMenuShareWeibo',
        'onMenuShareQZone', 'startRecord', 'stopRecord', 'onVoiceRecordEnd', 'playVoice',
        'pauseVoice', 'stopVoice', 'onVoicePlayEnd', 'uploadVoice', 'downloadVoice', 'chooseImage',
        'previewImage', 'uploadImage', 'downloadImage', 'translateVoice', 'getNetworkType',
        'openLocation', 'getLocation', 'hideOptionMenu', 'showOptionMenu', 'hideMenuItems',
        'showMenuItems', 'hideAllNonBaseMenuItem', 'showAllNonBaseMenuItem', 'closeWindow',
        'scanQRCode', 'chooseWXPay', 'openProductSpecificView', 'addCard', 'chooseCard', 'openCard'
    );

    /**
     * 构造方法
     */
    public function __construct(AccessToken $accessToken)
    {
        $this->accessToken = $accessToken;
    }

    /**
     * 注入接口
     */
    public function addApi($apis)
    {
        if( is_array($apis) ) {
            foreach( $apis AS $api ) {
                $this->addApi($api);
            }
        }

        $apiName = (string) $apis;

        if( !in_array($apiName, $this->apiValids, true) ) {
            throw new \InvalidArgumentException(sprintf('Invalid Api: %s', $apiName));
        }

        array_push($this->api, $apiName);

        return $this;
    }

    /**
     * 启用调试模式
     */
    public function enableDebug()
    {
        $this->debug = true;

        return $this;
    }

    /**
     * 获取配置文件
     * @param bool $asArray
     * @param null $url
     * @return array|bool|float|int|string
     */
    public function getConfig($asArray = false,$url = null)
    {
        $ticket = new Ticket($this->accessToken);

        if( $this->cache ) {
            $ticket->setCache($this->cache);
        }

        $options = array(
            'jsapi_ticket'  => $ticket->getTicketString(),
            'timestamp'     => Util::getTimestamp(),
            'url'           => ($url===null)?Util::getCurrentUrl():$url,
            'noncestr'      => Util::getRandomString(),
        );

        ksort($options);

        $signature = sha1(urldecode(http_build_query($options)));
        $configure = array(
            'appId'     => $this->accessToken['appid'],
            'nonceStr'  => $options['noncestr'],
            'timestamp' => $options['timestamp'],
            'signature' => $signature,
            'jsApiList' => $this->api,
            'debug'     => (bool) $this->debug
        );

        return $asArray ? $configure : Serializer::jsonEncode($configure);
    }

    /**
     * 输出对象
     */
    public function __toString()
    {
        return $this->getConfig();
    }
}
