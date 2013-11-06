<?php

namespace cipxml;

class CiscoIPPhoneInput extends CiscoIPPhoneDisplayableType{    
    protected $url = null;
    protected $input_items = array();

    public function __construct($title=null, $prompt=null, $url=null) {
        $this->setTitle($title);
        $this->setPrompt($prompt);
        $this->setURL($url);
    }

    public function setURL($url) {
        if($url && strlen($url)>256){
            throw new \LengthException('URL must have not more than 256 characters');
        }
        $this->url = $url;
    }

    public function addInputItem(InputItem $input_item) {
        if(count($this->input_items)>=5){
            throw new \OutOfBoundsException('A input can only hold up to 5 items');
        }
        $this->input_items[] = $input_item;
    }

    public function toXML(\DOMNode $domDoc){
        $root = $domDoc->createElement('CiscoIPPhoneInput');
        $domDoc->appendChild($root);
        
        parent::toXML($root);
        if($this->url){
            $url = $root->ownerDocument->createElement('URL');
            $url_text = $root->ownerDocument->createTextNode($this->url);
            $url->appendChild($url_text);
            $root->appendChild($url);
        }
        foreach ($this->input_items as $input_item) {
            $input_item->toXML($root);
        }
        return $domDoc;
    }

}
