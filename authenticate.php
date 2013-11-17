<?php

/*
 * @title Simple Cisco Auth Manager
 * @author Christian Bartsch <cb AT dreinulldrei DOT de>
 * @copyright (c) Christian Bartsch
 * @license GPL v2
 * @date 2013-11-16
 *
 * Provides basic security; certain features, like screenshot, will make the phone contact the
 * server and ask for authorization. These credentials must match the data in
 * "authenticate.config.inc.php", otherwise the request will be denied.
 *
 * Please do not use safe passwords used for other tasks, these will be transmitted over
 * the network without encryption and can therefore be sniffed. 
 */
 
require_once 'authenticate.config.inc.php';

$getUser = $_GET["UserID"];
$getPass = $_GET["Password"];
$getDevice = $_GET["devicename"];
$result = FALSE;

			 
for ($i = 0; $i <= (count($authdata)-1); $i++) {
    if ($authdata[$i]['devicename'] == $getDevice ) {
		if (($getUser == $authdata[$i]['UserID']) and ($getPass == $authdata[$i]['Password'])) {
			$result = TRUE;
		}
        break;
    }
}

if ($result) {
	echo "AUTHORIZED";
} else {
	echo "UN-AUTHORIZED";
}
?>
