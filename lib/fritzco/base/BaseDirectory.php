<?php

namespace fritzco\base;

use fritzco\interfaces\IDirectory;
use fritzco\interfaces\IContact;

class BaseDirectory implements IDirectory
{
    private $contacts = array();
    private $priority = 0;

    public static function getDirectories(){
        return null;
    }
    public static function getDirectory($id){
        return null;
    }
    public function getContacts(){
        return $this->contacts;
    }
    public function findContacts($query){
        return null;
    }
    
    protected function addContact(IContact $contact){
        $this->contacts[]=$contact;
    }
    
    public function getPriority(){
        return $this->priority;
    }
}

?>
