<?php
interface Directory
{
    public static function getDirectories();
    public static function getDirectory(string $id)
    public function getContacts();
    public function getContacts(string $query);
    public function getPriority();
}

?>
