<?php

namespace cipxml;

final class InputFlags {
    private function __construct() {}

    const A = 1;
    const T = 2;
    const N = 3;
    const E = 4;
    const U = 5;
    const L = 6;
    const AP = 7;
    const TP = 8;
    const NP = 9;
    const EP = 10;
    const UP = 11;
    const LP = 12;
    const PA = 7;
    const PT = 8;
    const PN = 9;
    const PE = 10;
    const PU = 11;
    const PL = 12;
    
    const _max = InputFlags::PL;
    
    public static function string($key){
        switch($key){
            case InputFlags::A: return 'A';
            case InputFlags::T: return 'T';
            case InputFlags::N: return 'N';
            case InputFlags::E: return 'E';
            case InputFlags::U: return 'U';
            case InputFlags::L: return 'L';
            case InputFlags::AP: return 'AP';
            case InputFlags::TP: return 'TP';
            case InputFlags::NP: return 'NP';
            case InputFlags::EP: return 'EP';
            case InputFlags::UP: return 'UP';
            case InputFlags::LP: return 'LP';
        }
    }
}

class InputItem extends XMLElement{
    protected $displayName = null;
    protected $queryStringParam = null;
    protected $inputFlags = null;
    protected $defaultValue = null;

    public function __construct($displayName=null, $queryStringParam=null, $inputFlags=InputFlags::A, $defaultValue=null) {
        $this->setDisplayName($displayName);
        $this->setQueryStringParam($queryStringParam);
        $this->setInputFlags($inputFlags);
        $this->setDefaultValue($defaultValue);
    }
    
    public function setDisplayName($displayName) {
        if($displayName && strlen($displayName)>32){
            throw new \LengthException('DisplayName must have not more than 32 characters');
        }
        $this->displayName = $displayName;
    }
    
    public function setQueryStringParam($queryStringParam) {
        if($queryStringParam && strlen($queryStringParam)>32){
            throw new \LengthException('QueryStringParam must have not more than 32 characters');
        }
        $this->queryStringParam = $queryStringParam;
    }
    
    public function setInputFlags($inputFlags) {
        if(!$inputFlags || $inputFlags>InputFlags::_max){
            throw new \InvalidArgumentException('no valid inputFlags, please use ENUM InputFlags to specify inputFlags (e.g. InputFlags::AP)');
        }
        $this->inputFlags = $inputFlags;
    }
    
    public function setDefaultValue($defaultValue) {
        if($defaultValue && strlen($defaultValue)>32){
            throw new \LengthException('DefaultValue must have not more than 32 characters');
        }
        $this->defaultValue = $defaultValue;
    }

    public function toXML(\DOMNode $domNode){
        $root = $domNode->ownerDocument->createElement('InputItem');
        $domNode->appendChild($root);
        
        if($this->displayName){
            $displayName = $root->ownerDocument->createElement('DisplayName');
            $displayName_text = $domNode->ownerDocument->createTextNode($this->displayName);
            $displayName->appendChild($displayName_text);
            $root->appendChild($displayName);
        }
        if($this->queryStringParam){
            $queryStringParam = $domNode->ownerDocument->createElement('QueryStringParam');
            $queryStringParam_text = $domNode->ownerDocument->createTextNode($this->queryStringParam);
            $queryStringParam->appendChild($queryStringParam_text);
            $root->appendChild($queryStringParam);
        }
        if($this->inputFlags){
            $inputFlags = $domNode->ownerDocument->createElement('InputFlags');
            $inputFlags_text = $domNode->ownerDocument->createTextNode(InputFlags::string($this->inputFlags));
            $inputFlags->appendChild($inputFlags_text);
            $root->appendChild($inputFlags);
        }
        if($this->defaultValue){
            $defaultValue = $domNode->ownerDocument->createElement('DefaultValue');
            $defaultValue_text = $domNode->ownerDocument->createTextNode($this->defaultValue);
            $defaultValue->appendChild($defaultValue_text);
            $root->appendChild($defaultValue);
        }
        return $domNode;
    }
}
