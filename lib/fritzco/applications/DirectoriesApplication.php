<?php

namespace fritzco\applications;

use fritzco\base\BaseApplication;
use fritzco\plugins\fritz\Directories;
use fritzco\base\DirectoryTextMenu;
use fritzco\base\ContactTextDirectory;
use cipxml\CiscoIPPhoneText;
use cipxml\SoftKeyItem;

class DirectoriesApplication extends BaseApplication
{
	function __construct() {
	    parent::__construct('fritzco/directories');
    }

	public function handle(){
		if(!isset($_GET["plugin"]) || !isset($_GET["book"])){
		}
		else{
			//get right plugin
			if($_GET["plugin"]=='fritz'){
				$directories = Directories::getDirectories();
			}
		
			$book = $directories->getDirectory($_GET["book"]);
			if(!isset($_GET["contact"])){
				if($book==NULL){
					$error = new CiscoIpPhoneText("ERROR", "ERROR", "ERROR");
					$error->setAppId($this->appId);
            		$error->addSoftKeyItem(new SoftKeyItem("ZURÜCK", 1, 'SoftKey:Exit'));
            		$error->toXML($this->domDoc);
            		return;
				}
				$menu = new DirectoryTextMenu($book);
				$menu->setAppId($this->appId);
			}
			else{
			    $contact=$book->getContact($_GET["contact"]);
			    if($contact==NULL){
					$error = new CiscoIpPhoneText("ERROR", "ERROR", "ERROR");
					$error->setAppId($this->appId);
            		$error->addSoftKeyItem(new SoftKeyItem("ZURÜCK", 1, 'SoftKey:Exit'));
            		$error->toXML($this->domDoc);
            		return;
				}
				$menu = new ContactTextDirectory($contact);
				$menu->setAppId($this->appId);
			}
		}
		$menu->toXML($this->domDoc);
	}
	
	public function __toString(){
        return $this->domDoc->saveXML();
    }
}

?>
