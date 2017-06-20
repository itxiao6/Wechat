<?php

namespace Itxiao6\Wechat\Event\Event;

use Itxiao6\Wechat\Event\Event;

class Subscribe extends Event
{
    public function isValid()
    {
        return ($this['MsgType'] === 'event')
            && ($this['Event'] === 'subscribe')
            && empty($this['EventKey']);
    }
}
