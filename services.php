<?php
/*
 * @author Christian Bartsch <cb AT dreinulldrei DOT de>
 * @portions Till Steinbach <till.steinbach@gmx.de>
 * @copyright (c) Christian Bartsch, Till Steinbach
 * @license GPL v2
 * @date 2013-12-16
 *
 * services.php displays pre-configured menu; otherwise create your own and run with parameters:
 * services.php?ip=192.168.1.20&amp;uid=user&amp;pass=secret&amp;cmd=dial&amp;dta=0123456789
 */

header("Content-type: text/xml");

require_once 'services.config.inc.php';
require_once 'services.locale.german.inc.php';
require_once __DIR__ . '/lib/cipxml/cipxml.php';

use cipxml\CiscoIPPhoneExecute;
use cipxml\CiscoIPPhoneMenu;
use cipxml\CiscoIPPhoneText;
use cipxml\CiscoIPPhoneInput;
use cipxml\CiscoIPPhoneResponse;
use cipxml\ExecuteItem;
use cipxml\ResponseItem;
use cipxml\InputItem;
use cipxml\InputFlags;
use cipxml\MenuItem;
use cipxml\SoftKeyItem;
use cipxml\KeyItem;
use cipxml\Key;

 
if (isset($_GET)) {
   if (isset($_GET["cmd"])) {
		if (isset($_GET["uid"])) {
			$getUser = $_GET["uid"];
		} else {
			$getUser = $default_uid;
		}
		if (isset($_GET["pwd"])) {
			$getPass = $_GET["pwd"];
		} else {
			$getPass = $default_pass;
		}
		if (isset($_GET["ip"])) {
			$getIP = $_GET["ip"];
		} else {
			$getIP = $default_ip;
		}
		if (isset($_GET["cmd"])) {
			$getCommand = $_GET["cmd"];
		}
		if (isset($_GET["dta"])) {
			$getData = $_GET["dta"];
		} else {
			$getData = '';
		}
	} else {
		$getCommand = 'default';
	}
} else {
	$getCommand = 'default';
}

switch ($getCommand) {
    case 'reboot':
        cmd_reboot($getUser, $getPass, $getIP, $getData, $usehttps);
        break;
	case 'ireboot':
        cmd_input_reboot($getUser, $getPass, $getIP, $getData);
        break;
	case 'idial':
        cmd_input_dial($getUser, $getPass, $getIP);
        break;
    case 'dial':
        cmd_dial($getUser, $getPass, $getIP, $getData, $usehttps);
        break;
    case 'display':
        cmd_display($getUser, $getPass, $getIP, $getData, $usehttps);
		break;
    case 'idisplay':
        cmd_input_display($getUser, $getPass, $getIP, $getData);
        break;
	default:
		cmd_default ($default_uid, $default_pass, $default_ip);
		break;
}

die;

function cmd_input_dial ($getUser, $getPass, $getIP) {

    $url = "http://" . $_SERVER["SERVER_NAME"] . $_SERVER["PHP_SELF"] . "?cmd=dial&uid=" . urlencode($getUser) . "&pwd=" . urlencode($getPass);
    $menu = new CiscoIpPhoneInput(SERV_TITLE_DIAL, SERV_INPUT_QUERY, $url);
    $menu->addInputItem(new InputItem(SERV_INPUT_TARGET, 'ip', InputFlags::E, $getIP));
    $menu->addInputItem(new InputItem(SERV_INPUT_NUMBER, 'dta', InputFlags::T, ''));
	echo (string) $menu;

}


function cmd_input_reboot ($getUser, $getPass, $getIP, $getData) {

    $url = "http://" . $_SERVER["SERVER_NAME"] .  $_SERVER["PHP_SELF"] . "?cmd=reboot&uid=" . urlencode($getUser) . "&pwd=" . urlencode($getPass);
    $menu = new CiscoIpPhoneInput(SERV_TITLE_REBOOT, SERV_INPUT_QUERY, $url);
    $menu->addInputItem(new InputItem(SERV_INPUT_TARGET, 'ip', InputFlags::E, $getIP));
    $menu->addInputItem(new InputItem(SERV_INPUT_PHONETYPE, 'dta', InputFlags::A, $getData));
	echo (string) $menu;

}

function cmd_input_display ($getUser, $getPass, $getIP) {

    $menu = new CiscoIpPhoneMenu(SERV_TITLE_DISPLAY, SERV_STATUS_DISPLAY_TOGGLE);
	$menu->addMenuItem(new MenuItem(SERV_TITLE_DISPLAY . " " . SERV_BUTTON_ON, "http://" . $_SERVER["SERVER_NAME"] .  $_SERVER["PHP_SELF"] ."?cmd=display&dta=1&uid=" . urlencode($getUser) . "&pwd=" . urlencode($getPass) . "&ip=" . urlencode($getIP)));
	$menu->addMenuItem(new MenuItem(SERV_TITLE_DISPLAY . " " . SERV_BUTTON_OFF, "http://" . $_SERVER["SERVER_NAME"] .  $_SERVER["PHP_SELF"] . "?cmd=display&dta=0&uid=" . urlencode($getUser) . "&pwd=" . urlencode($getPass) . "&ip=" . urlencode($getIP)));
	$menu->addMenuItem(new MenuItem(SERV_TITLE_DISPLAY . " " . SERV_BUTTON_STANDARD, "http://" . $_SERVER["SERVER_NAME"] .  $_SERVER["PHP_SELF"] . "?cmd=display&dta=2&uid=" . urlencode($getUser) . "&pwd=" . urlencode($getPass) . "&ip=" . urlencode($getIP)));
	$menu->addSoftKeyItem(new SoftKeyItem(SERV_BUTTON_SELECT, 1, 'SoftKey:Select'));
	$menu->addSoftKeyItem(new SoftKeyItem(SERV_BUTTON_EXIT, 3, 'SoftKey:Exit'));
	echo (string) $menu;

}

function cmd_default ($default_uid, $default_pass, $default_ip) {

    $menu = new CiscoIpPhoneMenu(SERV_SERVICES_TITLE, SERV_PLEASE_CHOOSE);
    $menu->addMenuItem(new MenuItem(SERV_TITLE_DISPLAY . " " . SERV_BUTTON_ON . "/" . SERV_BUTTON_OFF, "http://" . $_SERVER["SERVER_NAME"] .  $_SERVER["REQUEST_URI"] . "?cmd=idisplay&uid=" . urlencode($default_uid) . "&pwd=" . urlencode($default_pass) . "&ip=" . urlencode($default_ip)));
    $menu->addMenuItem(new MenuItem(SERV_REBOOT_PHONE, "http://" . $_SERVER["SERVER_NAME"] .  $_SERVER["REQUEST_URI"] . "?cmd=reboot&dta=79xx&uid=" . urlencode($default_uid) . "&pwd=" . urlencode($default_pass) . "&ip=" . urlencode($default_ip)));
	$menu->addMenuItem(new MenuItem(SERV_TESTCALL, "http://" . $_SERVER["SERVER_NAME"] .  $_SERVER["REQUEST_URI"] . "?cmd=idial&uid=" . urlencode($default_uid) . "&pwd=" . urlencode($default_pass) . "&ip=" . urlencode($default_ip)));
	$menu->addSoftKeyItem(new SoftKeyItem(SERV_BUTTON_SELECT, 1, 'SoftKey:Select'));
	$menu->addSoftKeyItem(new SoftKeyItem(SERV_BUTTON_EXIT, 3, 'SoftKey:Exit'));

	echo (string) $menu;
	
	return TRUE;
}

// reboot phone
function cmd_reboot ($getUser, $getPass, $getIP, $getData, $usehttps) {
	$phonetype = strtolower($getData);
	
	switch ($phonetype) {
		case '99':
			$phonetype = '99xx';
			break;
		default:
			$phonetype = '79xx';
			break;		
	}
	
	
	switch ($phonetype) {
		case '99xx' : // set dta=99xx for 99xx reset command
			$command[0] = array(0 => "Key:Applications", 1 => "1");
			$command[1] = array(0 => "Key:KeyPad4", 1 => ".2");
			$command[2] = array(0 => "Key:KeyPad4", 1 => ".2");
			$command[3] = array(0 => "Key:KeyPad1", 1 => ".2");
			$command[4] = array(0 => "Key:Soft3", 1 => ".2");
			break;
		default: // use 79xx command in all other cases
			$command[0] = array(0 => "Key:Settings", 1 => "1");
			$command[1] = array(0 => "Key:KeyPadStar", 1 => ".2");
			$command[2] = array(0 => "Key:KeyPadStar", 1 => ".2");
			$command[3] = array(0 => "Key:KeyPadPound", 1 => ".2");
			$command[4] = array(0 => "Key:KeyPadStar", 1 => ".2");
			$command[5] = array(0 => "Key:KeyPadStar", 1 => ".2");
			break;
	}
	
	for ($i = 0; $i <= (count($command)-1); $i++) {
		$execute = new CiscoIPPhoneExecute;
		$execute->addExecuteItem(new ExecuteItem($command[$i][0], 0));
		$response = $execute->execute($getIP, $getUser, $getPass, $usehttps);
		sleep ($command[$i][1]);
	}

	$xml_response = new SimpleXMLElement($response);
	
	if ((string)$xml_response->ResponseItem['Data'] == 'Success') {
		$response = SERV_STATUS_SUCCESS . '...';
	} else {
		if (isset($xml_response['Number'])) {
			if ($xml_response['Number'] == 4) {
				$response = SERV_STATUS_ERROR . ' UN-AUTHORIZED';
			} else {
				$response = SERV_STATUS_ERROR . ' #' . $xml_response['Number'] == 4;
			}
		} else {
			$response = SERV_STATUS_ERROR . ' Status=' . (string)$xml_response->ResponseItem['Status'] . '  Data=' . (string)$xml_response->ResponseItem['Data'];
		}
	}

	$menu = new CiscoIpPhoneText(SERV_TITLE_REBOOT, SERV_STATUS_REBOOT . " " . $getIP, $response);
    $menu->addSoftKeyItem(new SoftKeyItem(SERV_BUTTON_BACK, 3, 'SoftKey:Exit'));
	echo (string) $menu;
	
	return TRUE;
}

// dial; dial number on remote phone, $getData must be numeric longint only
function cmd_dial ($getUser, $getPass, $getIP, $getData, $usehttps) {
	$number = strval($getData);

	$execute = new CiscoIPPhoneExecute;
	// $execute->addExecuteItem(new ExecuteItem("Key:Speaker", 0));
	$execute->addExecuteItem(new ExecuteItem("Dial:" . $number, 0));
	$response = $execute->execute($getIP, $getUser, $getPass, $usehttps);
	
	$xml_response = new SimpleXMLElement($response);
	
	if ((string)$xml_response->ResponseItem['Data'] == 'Success') {
		$response = SERV_STATUS_SUCCESS . '...';
	} else {
		if (isset($xml_response['Number'])) {
			if ($xml_response['Number'] == 4) {
				$response = SERV_STATUS_ERROR . ' UN-AUTHORIZED';
			} else {
				$response = SERV_STATUS_ERROR . ' #' . $xml_response['Number'] == 4;
			}
		} else {
			$response = SERV_STATUS_ERROR . ' Status=' . (string)$xml_response->ResponseItem['Status'] . '  Data=' . (string)$xml_response->ResponseItem['Data'];
		}
	}

	$menu = new CiscoIpPhoneText(SERV_TITLE_DIAL, SERV_STATUS_NUMBERSENT, $response);
	$menu->addSoftKeyItem(new SoftKeyItem(SERV_BUTTON_BACK, 3, 'SoftKey:Exit'));
	echo (string) $menu;
	
	return TRUE;
 }


 // display; turn backlit phone display on or off
function cmd_display ($getUser, $getPass, $getIP, $getData, $usehttps) {
	// 0:45 = off, 45 mins; 1:120 = on, 120 mins; 2 = default
	$number = intval($getData[0]);
	$duration = substr(strrchr($getData, ":"), 1);
	if (!$duration) {
			$duration = '0';
	}
	
	$execute = new CiscoIPPhoneExecute;
	
	Switch ($number) {
		Case '0' :
			$execute->addExecuteItem(new ExecuteItem("Init:Services", 0));
			$execute->addExecuteItem(new ExecuteItem("Init:Directories", 0));
			$display_state = 'Off:' . $duration;
			$display_state_msg = SERV_STATUS_DISPLAY_OFF;
			break;
		Case '1' :
			$display_state = 'On:' . $duration;
			$display_state_msg = SERV_STATUS_DISPLAY_ON;
			break;
		Default :
			$display_state = 'Default';
			$display_state_msg = SERV_STATUS_DISPLAY_DEFAULT;
			break;
	}
	
	$execute->addExecuteItem(new ExecuteItem("Display:$display_state", 0));
	$response = $execute->execute($getIP, $getUser, $getPass, $usehttps);
	
	$menu = new CiscoIpPhoneText(SERV_TITLE_DISPLAY, $display_state_msg, $response);
	$menu->addSoftKeyItem(new SoftKeyItem(SERV_BUTTON_BACK, 3, 'SoftKey:Exit'));
	echo (string) $menu;
	
	return TRUE;
 }

?>
