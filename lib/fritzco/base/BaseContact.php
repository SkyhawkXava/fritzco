<?php

namespace fritzco\base;

use fritzco\interfaces\IContact;

class BaseContact implements IContact
{
    private $id=NULL;
    private $displayName;
    private $numbers = array();
    private $emails = array();
    
    function __construct($id) {
        $this->id = $id;
    }
    
    public function getDisplayName(){
        return $this->displayName;
    }
    
    public function setDisplayName($displayName){
        $this->displayName = $displayName;
    }
    
    public function getNumbers(){
        return $this->numbers;
    }
    public function getEmails(){
        return $this->emails;
    }
    
    public function addNumber($number){
        $this->numbers[] = $number;
    }
    
    public function addEmail($email){
        $this->emails[] = $email;
    }
}

?>
