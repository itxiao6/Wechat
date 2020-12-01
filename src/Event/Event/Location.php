<?php

namespace Itxiao6\Wechat\Event\Event;

use Itxiao6\Wechat\Event\Event;

class Location extends Event
{
    public function isValid()
    {
        return ($this['MsgType'] === 'location');
    }
}
