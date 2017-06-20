<?php

namespace Itxiao6\Wechat\Event;

use Itxiao6\Wechat\Message\Entity;
use Itxiao6\Wechat\Bridge\XmlResponse;
use Doctrine\Common\Collections\ArrayCollection;

abstract class Event extends ArrayCollection
{
    /**
     * response message entity
     */
    public function setResponse(Entity $entity)
    {
        $body = $entity->getBody();

        $body['ToUserName']     = $this['FromUserName'];
        $body['FromUserName']   = $this['ToUserName'];
        $body['MsgType']        = $entity->getType();
        $body['CreateTime']     = time();

        $response = new XmlResponse($body);
        $response->send();
    }

    /**
     * check event options is valid
     */
    abstract public function isValid();
}
