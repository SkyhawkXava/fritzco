<?php

namespace cipxml;

class DirectoryEntry extends XMLElement{
    protected $name = null;
    protected $telephone = null;

    public function __construct($name=null, $telephone=null) {
        $this->setName($name);
        $this->setTelephone($telephone);
    }
    
    public function setName($name) {
        if($name && strlen($name)>32){
            throw new \LengthException('Name must have not more than 64 characters');
        }
        $this->name = $name;
    }
    
    public function setTelephone($telephone, $correct=true) {
        if($correct){
            $telephone = preg_replace('/[^0-9+]/', '', $telephone);
        }
        if($telephone && strlen($telephone)>32){
            throw new \LengthException('Telephone must have not more than 32 characters');
        }
        $this->telephone = $telephone;
    }

    public function toXML(\DOMNode $domNode){
        $root = $domNode->ownerDocument->createElement('DirectoryEntry');
        $domNode->appendChild($root);
        
        if($this->name){
            $name = $root->ownerDocument->createElement('Name');
            $name_text = $domNode->ownerDocument->createTextNode($this->name);
            $name->appendChild($name_text);
            $root->appendChild($name);
        }
        if($this->telephone){
            $telephone = $root->ownerDocument->createElement('Telephone');
            $telephone_text = $domNode->ownerDocument->createTextNode($this->telephone);
            $telephone->appendChild($telephone_text);
            $root->appendChild($telephone);
        }
        return $domNode;
    }
}
