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
    
    protected function addDirectory($directory){
        $this->directoryList[] = $directory;
    }
}

?>
