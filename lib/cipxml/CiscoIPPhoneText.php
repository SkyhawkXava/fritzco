<?php

namespace cipxml;

class CiscoIPPhoneText extends CiscoIPPhoneDisplayableType{    
    protected $text = null;

    public function __construct($title=null, $prompt=null, $text=null) {
        $this->setTitle($title);
        $this->setPrompt($prompt);
        $this->setText($text);
    }

    public function setText($text) {
        if(strlen($text)>=4000){
            throw new \LengthException('Text must have not more than 4000 characters');
        }
        $this->text = $text;
    }

    public function toXML(\DOMNode $domDoc){
        $root = $domDoc->createElement('CiscoIPPhoneText');
        $domDoc->appendChild($root);
        
        parent::toXML($root);
        
        if($this->text){
            $text = $domDoc->createElement('Text');
            $text_text = $domDoc->createTextNode($this->text);
            $text->appendChild($text_text);
            $root->appendChild($text);
        }
        
        return $domDoc;
    }

}
