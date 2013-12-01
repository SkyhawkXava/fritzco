<?php

namespace fritzco\base;

use fritzco\interfaces\IDirectoryMenu;
use cipxml\CiscoIPPhoneDirectory;
use cipxml\DirectoryEntry;
use cipxml\XMLElement;
use cipxml\SoftKeyItem;
use cipxml\KeyItem;
use cipxml\Key;

class ContactTextDirectory extends CiscoIPPhoneDirectory implements IDirectoryMenu
{
	private $contact;
    
    function __construct($contact) {
    	parent::__construct("TODO PLUGIN?" . ' ' . "TODO Buch", $contact->getDisplayName());
        $this->contact = $contact;
    }

    public function toXML(\DOMNode $domNode) {
        $numbers = $this->contact->getNumbers();
    
    	if(count($numbers)>0){
            foreach($numbers as $number){ 
                $type = $number->getDisplayableType();
                $this->addDirectoryEntry(new DirectoryEntry($type, $number->getNumber()));
            }
            $this->addSoftKeyItem(new SoftKeyItem("PB_BUTTON_DIAL", 1, 'SoftKey:Dial'));
			$get = $_GET;
            unset($get['id']);
            $url = "http://" . $_SERVER["SERVER_NAME"] . $_SERVER["PHP_SELF"] . '?' . http_build_query($get);
            $this->addSoftKeyItem(new SoftKeyItem("PB_BUTTON_BACK", 2, $url));
            $url = "http://" . $_SERVER["SERVER_NAME"] . $_SERVER["PHP_SELF"] . '?' . http_build_query(array_merge($_GET,array("details"=>true)));
            $this->addSoftKeyItem(new SoftKeyItem("PB_BUTTON_DETAILS", 4, $url));
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
