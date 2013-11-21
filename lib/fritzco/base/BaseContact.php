<?php

namespace fritzco\base;

use fritzco\interfaces\IContact;

class BaseContact implements IContact
{
    private $displayName;
    private $numbers = array();
    
    public function getDisplayName(){
        return $this->displayName;
    }
    public function getNumbers(){
        return $this->numbers;
    }
}

?>
