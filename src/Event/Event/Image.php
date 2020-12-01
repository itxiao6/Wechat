<?php

namespace Itxiao6\Wechat\Event\Event;

use Itxiao6\Wechat\Event\Event;

class Image extends Event
{
    public function isValid()
    {
        return ($this['MsgType'] === 'image');
    }
}
