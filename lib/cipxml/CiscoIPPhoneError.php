<?php

namespace cipxml;

class CiscoIPPhoneError extends XMLElement{    
    protected $number = null;
    protected $message = null;

    public function __construct($number, $message=null) {
        $this->setNumber($number);
        $this->setMessage($message);
    }

    public function setNumber($number) {
        if(!is_numeric($number)){
            throw new \InvalidArgumentException('Number must be numeric');
        }
        $this->number = $number;
    }
    
    public function setMessage($message) {
        $this->message = $message;
    }

    public function toXML(\DOMNode $domDoc){
        $root = $domDoc->createElement('CiscoIPPhoneError');
        $domDoc->appendChild($root);
        
        if($this->number){
            $number = $domDoc->createAttribute('Number');
            $number->value = $this->number;
            $root->appendChild($number);
        }
        if($this->message){
            $text_text = $domDoc->createTextNode($this->message);
            $root->appendChild($text_text);
        }
        
        return $domDoc;
    }
    
    public static function XMLfactory($string)
    {
        $doc = new \DOMDocument();
        $doc->loadXML($string);
    
        $errors = $doc->getElementsByTagName("CiscoIPPhoneError");
        foreach( $errors as $error ){
            $number = $error->getAttribute('Number');
            $message = $error->nodeValue;
            return new CiscoIPPhoneError($number, $message);
        }
        return false;
    }
}
