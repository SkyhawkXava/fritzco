<?php

namespace cipxml;

class XMLElement{

    public function toXML(\DOMNode $domDoc){
        $root = $domDoc->createElement('MenuItem');
        $domDoc->appendChild($root);
        return $domDoc;
    }

    public function __toString() {
        $domDoc = new \DOMDocument();
        $domDoc->encoding='utf-8';
        return $this->toXML($domDoc)->saveXML();
    }
}
