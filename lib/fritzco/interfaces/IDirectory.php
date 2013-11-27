<?php

namespace fritzco\interfaces;

interface IDirectory
{
    public function getName();
    public function getContacts();
    public function findContacts($query);
    public function getPriority();
}

?>
