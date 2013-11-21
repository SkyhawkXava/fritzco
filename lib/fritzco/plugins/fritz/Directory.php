<?php

namespace fritzco\plugins\fritz;

use fritzco\base\BaseDirectory;
use fritzco\base\BaseContact;

class Directory extends BaseDirectory{
    public static function getDirectories(){
        $directory = new Directory();
        $directory->addContact(new BaseContact());
        return $directory;
    }
}

?>
