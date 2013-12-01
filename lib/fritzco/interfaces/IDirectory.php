<?php

namespace fritzco\interfaces;

interface IDirectory
{
	public function getId();
    public function getName();
    public function getContacts();
    public function getContact($id);
    public function findContacts($query);
    public function getPriority();
}

?>
