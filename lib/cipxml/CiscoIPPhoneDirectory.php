<?php

namespace cipxml;

class CiscoIPPhoneDirectory extends CiscoIPPhoneDisplayableType{    
    protected $directory_entries = array();

    public function __construct($title=null, $prompt=null) {
        $this->setTitle($title);
        $this->setPrompt($prompt);
    }

    public function addDirectoryEntry(DirectoryEntry $directory_entry) {
        if(count($this->directory_entries)>=32){
            throw new \OutOfBoundsException('A directory can only hold up to 32 items');
        }
        $this->directory_entries[] = $directory_entry;
    }

    public function toXML(\DOMNode $domDoc){
        $root = $domDoc->createElement('CiscoIPPhoneDirectory');
        $domDoc->appendChild($root);
        
        parent::toXML($root);
        
        foreach ($this->directory_entries as $directory_entry) {
            $directory_entry->toXML($root);
        }
        return $domDoc;
    }

}
