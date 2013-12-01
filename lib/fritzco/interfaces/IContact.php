<?php

namespace fritzco\interfaces;

interface IContact
{
	public function getId();
    public function getDisplayName();
    public function getNumbers();
    public function getEmails();
}

?>
