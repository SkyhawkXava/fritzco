<?php

namespace fritzco\interfaces;

interface IDirectory
{
    public static function getDirectories();
    public static function getDirectory($id);
    public function getContacts();
    public function findContacts($query);
    public function getPriority();
}

?>
