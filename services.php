<?php
/*
 * @author Christian Bartsch <cb AT dreinulldrei DOT de>
 * @portions Till Steinbach <till.steinbach@gmx.de>, Dave Gibbons <dave@dave.vc>
 * @copyright (c) Christian Bartsch, Till Steinbach, Dave Gibbons
 * @license GPL v2
 * @date 2013-11-17
 *
 * services.php displays pre-configured menu; otherwise create your own and run with parameters:
 * services.php?ip=192.168.1.20&amp;uid=user&amp;pass=secret&amp;cmd=dial&amp;dta=0123456789
 */

header("Content-type: text/xml");

require_once 'services.config.inc.php';
require_once 'services.locale.german.inc.php';
require_once __DIR__ . '/lib/cipxml/cipxml.php';

use cipxml\CiscoIPPhoneDirectory;
use cipxml\CiscoIPPhoneMenu;
use cipxml\CiscoIPPhoneText;
use cipxml\CiscoIPPhoneInput;
use cipxml\DirectoryEntry;
use cipxml\InputItem;
use cipxml\InputFlags;
use cipxml\MenuItem;
use cipxml\SoftKeyItem;
use cipxml\KeyItem;
use cipxml\Key;

 
if (isset($_GET)) {
   if (isset($_GET["cmd"])) {
		$getUser = $_GET["uid"];
		$getPass = $_GET["pwd"];
		$getIP = $_GET["ip"];
		$getCommand = $_GET["cmd"];
		$getData = $_GET["dta"];
	} else {
		$getCommand = 'default';
	}
} else {
	$getCommand = 'default';
}

if (empty($getUser)) {
	$getUser = $default_uid;
}
if (empty($getPass)) {
	$getPass = $default_pass;
}
if (empty($getIP)) {
	$getIP = $default_ip;
}


switch ($getCommand) {
    case 'reboot':
        cmd_reboot($getUser, $getPass, $getIP, $getData);
        break;
	case 'idial':
        cmd_inputdial($getUser, $getPass, $getIP);
        break;
    case 'dial':
        cmd_dial($getUser, $getPass, $getIP, $getData);
        break;
	default:
		cmd_default ($default_uid, $default_pass, $default_ip);
		break;
}

die;

function cmd_inputdial ($getUser, $getPass, $getIP) {

    $url = "http://" . $_SERVER["SERVER_NAME"] . $_SERVER["PHP_SELF"] . htmlentities("?cmd=dial&uid=" . urlencode($getUser) . "&pwd=" . urlencode($getPass));
    $menu = new CiscoIpPhoneInput(SERV_TITLE_DIAL, SERV_INPUT_QUERY, $url);
    $menu->addInputItem(new InputItem(SERV_INPUT_TARGET, 'ip', InputFlags::E, $getIP));
    $menu->addInputItem(new InputItem(SERV_INPUT_NUMBER, 'dta', InputFlags::T, ''));
	echo (string) $menu;

}

function cmd_default ($default_uid, $default_pass, $default_ip) {

    $menu = new CiscoIpPhoneMenu(SERV_SERVICES_TITLE, SERV_PLEASE_CHOOSE);
    $menu->addMenuItem(new MenuItem(SERV_REBOOT_PHONE, "http://" . $_SERVER["SERVER_NAME"] .  $_SERVER["REQUEST_URI"] . htmlentities("?cmd=reboot&dta=79xx&uid=" . urlencode($default_uid) . "&pwd=" . urlencode($default_pass) . "&ip=" . urlencode($default_ip))));
	$menu->addMenuItem(new MenuItem(SERV_TESTCALL, "http://" . $_SERVER["SERVER_NAME"] .  $_SERVER["REQUEST_URI"] . htmlentities("?cmd=idial&uid=" . urlencode($default_uid) . "&pwd=" . urlencode($default_pass) . "&ip=" . urlencode($default_ip))));
	$menu->addSoftKeyItem(new SoftKeyItem(SERV_BUTTON_SELECT, 1, 'SoftKey:Select'));
	$menu->addSoftKeyItem(new SoftKeyItem(SERV_BUTTON_EXIT, 2, 'SoftKey:Exit'));

	echo (string) $menu;
	
	return TRUE;
}

// reboot phone
function cmd_reboot ($getUser, $getPass, $getIP, $getData) {
	switch (strtolower($getData)) {
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
	
	$response = '';
	
	for ($i = 0; $i <= (count($command)-1); $i++) {
		$response = push2phone($getIP,$command[$i][0],$getUser,$getPass);
		sleep (0.1);
	}
	
	$menu = new CiscoIpPhoneText(SERV_TITLE_REBOOT, SERV_STATUS_REBOOT . " " . $getIP, $response);
    $menu->addSoftKeyItem(new SoftKeyItem(SERV_BUTTON_BACK, 2, 'SoftKey:Exit'));
	echo (string) $menu;
	
	return TRUE;
}

// dial; dial number on remote phone, $getData must be numeric longint only
function cmd_dial ($getUser, $getPass, $getIP, $getData) {
	$number = strval($getData);
	sleep (0.5);
	$response = push2phone($getIP,"Key:Speaker" ,$getUser,$getPass);
	sleep (0.5);

	for ($i = 0; $i <= (strlen($number)); $i++) {
		$response = push2phone($getIP,"Key:KeyPad" . $number[$i],$getUser,$getPass);
		sleep (0.2);
		}
	
	$menu = new CiscoIpPhoneText(SERV_TITLE_DIAL, SERV_STATUS_NUMBERSENT, $response);
	$menu->addSoftKeyItem(new SoftKeyItem(SERV_BUTTON_BACK, 2, 'SoftKey:Exit'));
	echo (string) $menu;
	
	return TRUE;
 
}

/* this is the function that does all of the dirty work.
from http://www.voip-info.org/wiki-Cisco+79XX+XML+Push */
function push2phone($ip, $uri, $uid, $pwd){
  $auth = base64_encode($uid.":".$pwd);
  $xml  = "<CiscoIPPhoneExecute><ExecuteItem Priority=\"0\" URL=\"".$uri."\"/></CiscoIPPhoneExecute>";
  // $response = $xml."\n";
  $xml  = "XML=".urlencode($xml);
  
  $post  = "POST /CGI/Execute HTTP/1.0\r\n";
  $post .= "Host: $ip\r\n";
  $post .= "Authorization: Basic $auth\r\n";
  $post .= "Connection: close\r\n";
  $post .= "Content-Type: application/x-www-form-urlencoded\r\n";
  $post .= "Content-Length: ".strlen($xml)."\r\n\r\n";
  
  $response = '';
  
  $fp = fsockopen ($ip, 80, $errno, $errstr, 10);
  if(!$fp){ $response.= "$errstr ($errno)\n"; }
  else
  {
    fputs($fp, $post.$xml);
    flush();
    while (!feof($fp))
    {
     $response .= fgets($fp, 128);
     flush();
    }
  }
  return $response;
}
?>
