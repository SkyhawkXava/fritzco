<?php

namespace fritzco\base;

use fritzco\interfaces\IDirectory;
use fritzco\interfaces\IContact;

class BaseDirectory implements IDirectory
{
    private $id=NULL;
    private $name;
    private $contacts = array();
    private $priority = 0;

    function __construct($id) {
        $this->id = $id;
    }

    public function getName(){
        return $this->name;
    }
    
    public function setName($name){
        $this->name = $name;
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
