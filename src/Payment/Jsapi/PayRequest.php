<?php

namespace Itxiao6\Wechat\Payment\Jsapi;

class PayRequest extends ConfigGenerator
{
    /**
     * 分解配置
     */
    public function resolveConfig()
    {
        return $this->toArray();
    }
}
