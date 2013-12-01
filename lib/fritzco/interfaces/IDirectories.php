<?php

namespace fritzco\interfaces;

interface IDirectories
{
    public static function getDirectories();
    public function getDirectoryList();
    public function getDirectory($id);
}

?>
