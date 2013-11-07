<?php

namespace cipxml;

class ResponseItem extends XMLElement{
    protected $status = null;
    protected $data = null;
    protected $url = null;

    public function __construct($status, $data, $url) {
        $this->setStatus($status);
        $this->setData($data);
        $this->setURL($url);
    }
    public function setStatus($status) {
        $this->status = $status;
    }
    
    public function setData($data) {
        if(!$data || strlen($data)>4000){
            throw new \LengthException('Data is required and must have not more than 4000 characters');
        }
        $this->data = $data;
    }
    
    public function setURL($url) {
        if(!$url || strlen($url)>256){
            throw new \LengthException('URL is required and must have not more than 256 characters');
        }
        $this->url = $url;
    }

    public function toXML(\DOMNode $domNode){
        $root = $domNode->ownerDocument->createElement('ResponseItem');
        $domNode->appendChild($root);
        
        if(true){
            $status = $root->ownerDocument->createAttribute('Status');
            $status->value = $this->status;
            $root->appendChild($status);
        }
        if($this->data){
            $data = $root->ownerDocument->createAttribute('Data');
            $data->value = $this->data;
            $root->appendChild($data);
        }
        if($this->url){
            $url = $domNode->ownerDocument->createAttribute('URL');
            $url->value = $this->url;
            $root->appendChild($url);
        }
        return $domNode;
    }
}
