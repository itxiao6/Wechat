<?php

namespace Itxiao6\Wechat\Event\Event;

use Itxiao6\Wechat\Event\Event;

class UserLocation extends Event
{
    public function isValid()
    {
        return ($this['MsgType'] === 'event')
            && ($this['Event'] === 'LOCATION');
    }
}