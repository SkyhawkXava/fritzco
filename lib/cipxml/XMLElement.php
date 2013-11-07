<?php

namespace cipxml;

class XMLElement{

    public function isValid(){
        $domDoc = new \DOMDocument();
        $domDoc->encoding='utf-8';
        $this->toXML($domDoc);
        return $domDoc->schemaValidate(dirname(__FILE__).'/schema.xsd');
    }

    public function __toString() {
        $domDoc = new \DOMDocument();
        $domDoc->encoding='utf-8';
        return $this->toXML($domDoc)->saveXML();
    }
}
