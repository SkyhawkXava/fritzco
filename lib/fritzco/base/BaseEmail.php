<?php

namespace fritzco\base;

use fritzco\interfaces\IEmail;

class BaseEmail implements IEmail
{
    private $type;
    private $other_type;
    private $address;
    
    public function setType($type=0, $other_type=NULL){
        if($type<0 || $type>EmailType::_max){
            throw new \InvalidArgumentException('no valid number type, please use ENUM EmailType to specify type (e.g. EmailType::BUSINESS)');
        }
        if($type==EmailType::OTHER){
            $this->other_type = $other_type;
        }
        $this->type = $type;
    }
    public function getType(){
        return $this->number;
    }
    public function setAddress($address){
        $this->address = $address;
    }
    
    public function getAddress(){
        return $this->address;
    }
}

?>
