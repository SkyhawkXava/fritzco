<?php

namespace cipxml;

class CiscoIPPhoneMenu extends CiscoIPPhoneDisplayableType{    
    protected $menu_items = array();

    public function __construct($title=null, $prompt=null) {
        $this->setTitle($title);
        $this->setPrompt($prompt);
    }

    public function addMenuItem(MenuItem $menu_item) {
        if(count($this->menu_items)>=100){
            throw new \OutOfBoundsException('A menu can only hold up to 100 items');
        }
        $this->menu_items[] = $menu_item;
    }

    public function toXML(\DOMNode $domDoc){
        $root = $domDoc->createElement('CiscoIPPhoneMenu');
        $domDoc->appendChild($root);
        
        parent::toXML($root);
        
        foreach ($this->menu_items as $menu_item) {
            $menu_item->toXML($root);
        }
        return $domDoc;
    }

}
