<?php

namespace cipxml;

class CiscoIPPhoneResponse extends XMLElement{
    protected $response_items = array();    

    public function addResponseItem(ResponseItem $response_item) {
        if(count($this->response_items)>=3){
            throw new \OutOfBoundsException('A response can only hold up to 3 items');
        }
        $this->response_items[] = $response_item;
    }

    public function toXML(\DOMNode $domDoc){
        $root = $domDoc->createElement('CiscoIPPhoneResponse');
        $domDoc->appendChild($root);
        
        foreach ($this->response_items as $response_item) {
            $response_item->toXML($root);
        }
        return $domDoc;
    }
    
    public static function XMLfactory($string)
    {
        $doc = new \DOMDocument();
        $doc->loadXML($string);
    
        $responses = $doc->getElementsByTagName('CiscoIPPhoneResponse');
        foreach( $responses as $response ){
            $return = new CiscoIPPhoneResponse();
            $response_items = $response->getElementsByTagName('ResponseItem');
            foreach( $response_items as $response_item ){
                $status_value = $response_item->getAttributeNode('Status')->value;
                $data_value = $response_item->getAttributeNode('Data')->value;
                $URL_value = $response_item->getAttributeNode('URL')->value;
                $return->addResponseItem(new ResponseItem($status_value, $data_value, $URL_value));
            }
            return $return;
        }
        return false;
    }

}
