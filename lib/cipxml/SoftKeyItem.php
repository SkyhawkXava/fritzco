<?php

namespace cipxml;

class SoftKeyItem extends XMLElement{
    protected $name = null;
    protected $position = null;
    protected $url = null;
    protected $url_down = null;

    public function __construct($name=null, $position=null, $url=null, $url_down=null) {
        $this->setName($name);
        $this->setPosition($position);
        $this->setURL($url);
        $this->setURLDown($url_down);
    }
    
    public function setName($name) {
        if($name && strlen($name)>32){
            throw new \LengthException('Name must have not more than 32 characters');
        }
        $this->name = $name;
    }
    
    public function setPosition($position) {
        if($position && ($position<1 || $position>8)){
            throw new \OutOfRangeException('Position must be betwee 1 and 8');
        }
        $this->position = $position;
    }
    
    public function getPosition() {
        return $this->position;
    }
    
    public function setURL($url) {
        if($url && strlen($url)>256){
            throw new \LengthException('URL must have not more than 256 characters');
        }
        $this->url = $url;
    }
    
    public function setURLDown($url_down) {
        if($url_down && strlen($url_down)>256){
            throw new \LengthException('URL must have not more than 256 characters');
        }
        $this->url_down = $url_down;
    }


    public function toXML(\DOMNode $domNode){
        $root = $domNode->ownerDocument->createElement('SoftKeyItem');
        $domNode->appendChild($root);
        
        if($this->name){
            $name = $root->ownerDocument->createElement('Name');
            $name_text = $domNode->ownerDocument->createTextNode($this->name);
            $name->appendChild($name_text);
            $root->appendChild($name);
        }
        if($this->position){
            $position = $root->ownerDocument->createElement('Position');
            $position_text = $domNode->ownerDocument->createTextNode($this->position);
            $position->appendChild($position_text);
            $root->appendChild($position);
        }
        if($this->url){
            $url = $root->ownerDocument->createElement('URL');
            $url_text = $domNode->ownerDocument->createTextNode($this->url);
            $url->appendChild($url_text);
            $root->appendChild($url);
        }
        if($this->url_down){
            $url_down = $root->ownerDocument->createElement('URLDown');
            $url_down_text = $domNode->ownerDocument->createTextNode($this->url_down);
            $url_down->appendChild($url_down_text);
            $root->appendChild($url_down);
        }
        return $domNode;
    }
}
