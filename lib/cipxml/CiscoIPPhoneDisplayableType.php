<?php

namespace cipxml;

final class KeypadTarget {
    private function __construct() {}
    
    const application = 1;
    const applicationCall = 2;
    const activeCall = 3;
    
    const _max = KeypadTarget::activeCall;
    
    public static function string($keypadTarget){
        switch($keypadTarget){
            case KeypadTarget::application: return 'application';
            case KeypadTarget::applicationCall: return 'applicationCall';
            case KeypadTarget::activeCall: return 'activeCall';
        }
    }
}

class CiscoIPPhoneDisplayableType extends XMLElement{
    protected $title = null;
    protected $prompt = null;
    protected $softkey_items = array();
    protected $key_items = array();
    protected $keypadTarget = null;
    protected $appId = null;
    protected $onAppFocusLost = null;
    protected $onAppFocusGained = null;
    protected $onAppMinimized = null;
    protected $onAppClosed = null;

    public function setTitle($title) {
        if($title && strlen($title)>32){
            throw new \LengthException('Title must have not more than 32 characters');
        }
        $this->title = $title;
    }
    
    public function setPrompt($prompt) {
        if($prompt && strlen($prompt)>32){
            throw new \LengthException('Prompt must have not more than 32 characters');
        }
        $this->prompt = $prompt;
    }

   public function addSoftKeyItem(SoftKeyItem $softkey_item, $replace=true) {
        if(!$replace && $this->softkey_items[$softkey_item->getPosition()-1]){
            throw new \UnexpectedValueException('this position is already used');
        }
        if($softkey_item->getPosition()>=8){
            throw new \OutOfBoundsException('A displayable can only hold up to 8 softkeys');
        }
        $this->softkey_items[$softkey_item->getPosition()-1] = $softkey_item;
    }
    
    public function addKeyItem(KeyItem $key_item, $replace=true) {
        if($replace){
            for($i=0; $i<count($this->key_items); ++$i){
                if($this->key_items[$i]->getKey() == $key_item->getKey()){
                    unset($this->key_items[$i]);
                    break;
                }
            }
        }
        if(count($this->key_items)>=32){
            throw new \OutOfBoundsException('A displayable can only hold up to 32 keys');
        }
        $this->key_items[] = $key_item;
    }

    public function setKeypadTarget($keypadTarget) {
        if($keypadTarget && $keypadTarget>KeypadTarget::_max){
            throw new \InvalidArgumentException('no valid number, please use ENUM KeypadTarget to specify keypadTarget (e.g. KeypadTarget::application)');
        }
        $this->keypadTarget = $keypadTarget;
    }

    public function setAppId($appId) {
        if($appId && (strlen($appId)<1 || strlen($appId)>64)){
            throw new \LengthException('appId must be at least 1 character, but not more than 64 characters');
        }
        $this->appId = $appId;
    }

    public function setOnAppFocusLost($onAppFocusLost) {
        if($onAppFocusLost && (strlen($onAppFocusLost)<1 || strlen($onAppFocusLost)>256)){
            throw new \LengthException('onAppFocusLost must be at least 1 character, but not more than 256 characters');
        }
        $this->onAppFocusLost = $onAppFocusLost;
    }
    
    public function setOnAppFocusGained($onAppFocusGained) {
        if($onAppFocusGained && (strlen($onAppFocusGained)<1 || strlen($onAppFocusGained)>256)){
            throw new \LengthException('onAppFocusGained must be at least 1 character, but not more than 256 characters');
        }
        $this->onAppFocusGained = $onAppFocusGained;
    }
    
    public function setOnAppMinimized($onAppMinimized) {
        if($onAppMinimized && (strlen($onAppMinimized)<1 || strlen($onAppMinimized)>256)){
            throw new \LengthException('onAppMinimized must be at least 1 character, but not more than 256 characters');
        }
        $this->onAppMinimized = $onAppMinimized;
    }
    
    public function setOnAppClosed($onAppClosed) {
        if($onAppClosed && (strlen($onAppClosed)<1 || strlen($onAppClosed)>256)){
            throw new \LengthException('onAppClosed must be at least 1 character, but not more than 256 characters');
        }
        $this->onAppClosed = $onAppClosed;
    }

    public function toXML(\DOMNode $domNode){
        if($this->title){
            $title = $domNode->ownerDocument->createElement('Title');
            $title_text = $domNode->ownerDocument->createTextNode($this->title);
            $title->appendChild($title_text);
            $domNode->appendChild($title);
        }
        if($this->prompt){
            $prompt = $domNode->ownerDocument->createElement('Prompt');
            $prompt_text = $domNode->ownerDocument->createTextNode($this->prompt);
            $prompt->appendChild($prompt_text);
            $domNode->appendChild($prompt);
        }
        if($this->keypadTarget){
            $domAttribute = $domNode->ownerDocument->createAttribute('keypadTarget');
            $domAttribute->value = KeypadTarget::string($this->keypadTarget);
            $domNode->appendChild($domAttribute);
        }
        else if(isset($GLOBALS['keypadTarget'])){
            $domAttribute = $domNode->ownerDocument->createAttribute('keypadTarget');
            $domAttribute->value = KeypadTarget::string($GLOBALS['keypadTarget']);
            $domNode->appendChild($domAttribute);
        }
        if($this->appId){
            $domAttribute = $domNode->ownerDocument->createAttribute('appId');
            $domAttribute->value = $this->appId;
            $domNode->appendChild($domAttribute);
        }
        else if(isset($GLOBALS['appId'])){
            $domAttribute = $domNode->ownerDocument->createAttribute('appId');
            $domAttribute->value = $GLOBALS['appId'];
            $domNode->appendChild($domAttribute);
        }
        if($this->onAppFocusLost){
            $domAttribute = $domNode->ownerDocument->createAttribute('onAppFocusLost');
            $domAttribute->value = $this->onAppFocusLost;
            $domNode->appendChild($domAttribute);
        }
        if($this->onAppFocusGained){
            $domAttribute = $domNode->ownerDocument->createAttribute('onAppFocusGained');
            $domAttribute->value = $this->onAppFocusGained;
            $domNode->appendChild($domAttribute);
        }
        if($this->onAppMinimized){
            $domAttribute = $domNode->ownerDocument->createAttribute('onAppMinimized');
            $domAttribute->value = $this->onAppMinimized;
            $domNode->appendChild($domAttribute);
        }
        if($this->onAppClosed){
            $domAttribute = $domNode->ownerDocument->createAttribute('onAppClosed');
            $domAttribute->value = $this->onAppClosed;
            $domNode->appendChild($domAttribute);
        }
        foreach ($this->softkey_items as $softkey_item) {
            $softkey_item->toXML($domNode);
        }
        foreach ($this->key_items as $key_item) {
            $key_item->toXML($domNode);
        }
    }
}
