<?php

namespace cipxml;

final class Priority {
    private function __construct() {}
    
    const _0 = 0;
    const Immediately = 0;
    const _1 = 1;
    const WhenIdle = 1;
    const _2 = 2;
    const IfIdle = 2;
    
    const _max = Priority::IfIdle;
}

class ExecuteItem extends XMLElement{
    protected $priority = null;
    protected $url = null;

    public function __construct($url, $priority=null) {
        $this->setURL($url);
        $this->setPriority($priority);
    }
    
    public function setURL($url) {
        if(!$url || strlen($url)>256){
            throw new \LengthException('URL is required and must have not more than 256 characters');
        }
        $this->url = $url;
    }
    
    public function setPriority($priority) {
        if($priority && $priority>Priority::_max){
            throw new \InvalidArgumentException('no valid priority, please use ENUM Priority to specify priority (e.g. Priority::WhenIdle)');
        }
        $this->priority = $priority;
    }

    public function toXML(\DOMNode $domNode){
        $root = $domNode->ownerDocument->createElement('ExecuteItem');
        $domNode->appendChild($root);
        
        if($this->priority){
            $priority = $root->ownerDocument->createAttribute('Priority');
            $priority->value = $this->priority;
            $root->appendChild($priority);
        }
        if($this->url){
            $url = $domNode->ownerDocument->createAttribute('URL');
            $url->value = $this->url;
            $root->appendChild($url);
        }
        return $domNode;
    }
}
