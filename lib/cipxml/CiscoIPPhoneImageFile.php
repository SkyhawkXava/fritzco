<?php

namespace cipxml;

class CiscoIPPhoneImageFile extends CiscoIPPhoneDisplayableType{    
    protected $name = null;
	protected $prompt = null;
    protected $url = null;
	protected $locX = null;
	protected $locY = null;


    public function __construct($title=null, $prompt=null, $url=null, $locX=null, $locY=null) {
        $this->setTitle($title);
        $this->setPrompt($prompt);
		$this->setURL($url);
		$this->setLocX($locX);
		$this->setLocY($locY);
    }

    public function setURL($url) {
        if($url && strlen($url)>256){
            throw new \LengthException('URL must have not more than 256 characters');
        }
        $this->URL = $url;
	}
	
    public function setLocX($locX) {
        if($locX >297){
            throw new \LengthException('LocationX must be smaller than 297');
        }
        $this->LocationX = $locX;
	}
	
    public function setLocY($locY) {
        if($locY >167){
            throw new \LengthException('LocationY must be smaller than 167');
        }
        $this->LocationY = $locY;
	}
	

	    public function toXML(\DOMNode $domDoc){
        $root = $domDoc->createElement('CiscoIPPhoneImageFile');
        $domDoc->appendChild($root);
        
        parent::toXML($root);
        
        if($this->URL){
            $url = $domDoc->createElement('URL');
            $url_url = $domDoc->createTextNode($this->URL);
            $url->appendChild($url_url);
            $root->appendChild($url);
        }
        
		if($this->LocationX){
            $locX = $domDoc->createElement('LocationX');
            $locX_locX= $domDoc->createTextNode($this->LocationX);
            $locX->appendChild($locX_locX);
            $root->appendChild($locX);
        }
        
		if($this->LocationY){
            $locY = $domDoc->createElement('LocationY');
            $locY_locY= $domDoc->createTextNode($this->LocationY);
            $locY->appendChild($locY_locY);
            $root->appendChild($locY);
        }  
		
		
        return $domDoc;
    }

}
