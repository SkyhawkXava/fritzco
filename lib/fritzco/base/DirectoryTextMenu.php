<?php

namespace fritzco\base;

use fritzco\interfaces\IDirectoryMenu;
use cipxml\CiscoIPPhoneMenu;
use cipxml\MenuItem;
use cipxml\XMLElement;
use cipxml\SoftKeyItem;
use cipxml\KeyItem;
use cipxml\Key;

class DirectoryTextMenu extends CiscoIPPhoneMenu implements IDirectoryMenu
{
	private $directory;
	private $max_length = 30;
    
    function __construct($directory) {
    	parent::__construct("TODO PLUGIN?" . ' ' . "TODO Buch", $directory->getName());
        $this->directory = $directory;
    }

    public function toXML(\DOMNode $domNode) {
        $offset = 0;
        if(isset($_GET["offset"])){
            $offset = (int) $_GET["offset"];
        }
        
        $contacts = $this->directory->getContacts();
    
    	if(count($contacts)>0){
            for ($i = $offset; $i < count($contacts) && $i<$offset+$this->max_length; ++$i){ 
                $url = "http://" . $_SERVER["SERVER_NAME"] . $_SERVER["PHP_SELF"] . '?' . http_build_query(array_merge($_GET,array("contact"=>$contacts[$i]->getId())));
                $this->addMenuItem(new MenuItem($contacts[$i]->getDisplayName(), $url));
            }
            
            $get = $_GET;
			unset($get['offset']);
        	if($offset>0){
            	$newoffset = $offset-$this->max_length;
            	if($newoffset<0){
                	$newoffset=0;
            	}
            	$url = "http://" . $_SERVER["SERVER_NAME"] . $_SERVER["PHP_SELF"] . '?' . http_build_query(array_merge($get,array("offset"=>$newoffset)));
            	$this->addSoftKeyItem(new SoftKeyItem("PB_BUTTON_PREVIOUS_PAGE", 3, $url));
				$this->addKeyItem(new KeyItem(Key::NavLeft,$url));
        	}
        	if(($offset+$this->max_length)<count($contacts)){
            	$newoffset = $offset+$this->max_length;
            	$url = "http://" . $_SERVER["SERVER_NAME"] . $_SERVER["PHP_SELF"] . '?' . http_build_query(array_merge($get,array("offset"=>$newoffset)));
            	$this->addSoftKeyItem(new SoftKeyItem("PB_BUTTON_NEXT_PAGE", 4, $url));
				$this->addKeyItem(new KeyItem(Key::NavRight,$url));
        	}
            
    	}
    	else{
    	    $menu = new CiscoIpPhoneText(PB_NAME_GENERAL . ' ' . PB_PHONEBOOK, PB_NO_ENTRIES, PB_NO_ENTRIES_DESC);
            $menu->addSoftKeyItem(new SoftKeyItem(PB_BUTTON_BACK, 1, 'SoftKey:Exit'));
            return $menu->toXML($domNode);
    	}
    
        return parent::toXML($domNode);
    }
}

?>
