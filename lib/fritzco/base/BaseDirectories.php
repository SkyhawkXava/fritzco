<?php

namespace fritzco\base;

use fritzco\interfaces\IDirectories;
use fritzco\interfaces\IDirectory;

class BaseDirectories implements IDirectories
{
    private $directoryList = array();

    public static function getDirectories(){
        return null;
    }
    public function getDirectoryList(){
        return $this->directoryList;
    }
    
    public function getDirectory($id){
    	foreach($this->directoryList as $directory){
    		if($directory->getId()==$id){
    			return $directory;
    		}
    	}
        return NULL;
    }
    
    protected function addDirectory($directory){
        $this->directoryList[] = $directory;
    }
}

?>
