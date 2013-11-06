<?php

namespace cipxml;

class MenuItem extends XMLElement{
    protected $name = null;
    protected $url = null;

    public function __construct($name=null, $url=null) {
        $this->setName($name);
        $this->setURL($url);
    }
    
    public function setName($name) {
        if($name && strlen($name)>64){
            throw new \LengthException('Name must have not more than 64 characters');
        }
        $this->name = $name;
    }
    
    public function setURL($url) {
        if($url && strlen($url)>256){
            throw new \LengthException('URL must have not more than 256 characters');
        }
        $this->url = $url;
    }

    public function toXML(\DOMNode $domNode){
        $root = $domNode->ownerDocument->createElement('MenuItem');
        $domNode->appendChild($root);
        
        if($this->name){
            $name = $root->ownerDocument->createElement('Name');
            $name_text = $domNode->ownerDocument->createTextNode($this->name);
            $name->appendChild($name_text);
            $root->appendChild($name);
        }
        if($this->url){
            $url = $domNode->ownerDocument->createElement('URL');
            $url_text = $domNode->ownerDocument->createTextNode($this->url);
            $url->appendChild($url_text);
            $root->appendChild($url);
        }
        return $domNode;
    }
}
