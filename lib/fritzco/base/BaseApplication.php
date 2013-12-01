<?php

namespace fritzco\base;

use fritzco\interfaces\IApplication;

class BaseApplication implements IApplication
{
	protected $domDoc = NULL;
	protected $appId = NULL;
	
	function __construct($appId) {
	    $this->appId = $appId;
	
        $this->domDoc = new \DOMDocument();
        $this->domDoc->encoding='utf-8';
    }
    
    public function getDOMDoc(){
    	return $this->domDoc;
    }
    
    public function getAppId(){
    	return $this->appId;
    }
    
    public function __toString(){
        return $this->domDoc->saveXML();
    }
}

?>
