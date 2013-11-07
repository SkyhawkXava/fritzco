<?php

namespace cipxml;

class CiscoIPPhoneExecute extends XMLElement{
    protected $execute_items = array();    

    public function addExecuteItem(ExecuteItem $execute_item) {
        if(count($this->execute_items)>=3){
            throw new \OutOfBoundsException('An execute can only hold up to 3 items');
        }
        $this->execute_items[] = $execute_item;
    }

    public function toXML(\DOMNode $domDoc){
        $root = $domDoc->createElement('CiscoIPPhoneExecute');
        $domDoc->appendChild($root);
        
        foreach ($this->execute_items as $execute_item) {
            $execute_item->toXML($root);
        }
        return $domDoc;
    }
    
    public function execute($phone_host, $user=null, $password=null, $use_https=true){
        $post = curl_init();
        $postvar = array('XML'=>(string)$this);
        curl_setopt($post, CURLOPT_URL, ($use_https?'https://':'http://') . $phone_host . '/CGI/Execute');
        if($user){
            if($password){
                curl_setopt($post, CURLOPT_USERPWD, $user . ":" . $password);
            }
            else{
                curl_setopt($post, CURLOPT_USERPWD, $user);
            }
        }
        curl_setopt($post, CURLOPT_POST, true);
        curl_setopt($post, CURLOPT_POSTFIELDS, http_build_query($postvar));
        curl_setopt($post, CURLOPT_RETURNTRANSFER, 1);
        
        $result = curl_exec($post);
        curl_close($post);
        if($result !== false){
            $object = CiscoIPPhoneResponse::XMLfactory($result);
            if($object){
                return $object;
            }
            else{
                $object = CiscoIPPhoneError::XMLfactory($result);
                if($object){
                    return $object;
                }
            }
        }
        return false;
    }

}
