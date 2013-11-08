<?php
/**
 * @author Till Steinbach <till.steinbach@gmx.de>
 * @copyright (c) Till Steinbach
 * @license GPL v2
 */

require_once 'config.inc.php';
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

header("Content-type: text/xml");

$translation = array("home" => "Privat", "mobile" => "Mobil", "work" => "Geschäftlich", "fax" => "Fax", "fax_work" => "Fax Geschäftlich", "private" => "Privat", "business" => "Geschäftlich", "other" => "Sonstige");

if(isset($_GET["refresh"]))
{
    $fritzCfg    = 'http://fritz.box/cgi-bin/firmwarecfg';
    $telefonbuch = 1; // Auswahl des Telefonbuches 

    $ch = curl_init('http://fritz.box/login_sid.lua');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $login = curl_exec($ch);
    $session_status_simplexml = simplexml_load_string($login);
    if ($session_status_simplexml->SID != '0000000000000000'){
       $SID = $session_status_simplexml->SID;
    }
    else
    {
       $challenge = $session_status_simplexml->Challenge;
       $response = $challenge . '-' . md5(mb_convert_encoding($challenge . '-' . $fritzbox_password, "UCS-2LE", "UTF-8"));
       curl_setopt($ch, CURLOPT_POSTFIELDS, "response={$response}&page=/login_sid.lua");
       $sendlogin = curl_exec($ch);
       $session_status_simplexml = simplexml_load_string($sendlogin);

       if ($session_status_simplexml->SID != '0000000000000000'){
           $SID = $session_status_simplexml->SID;
       }
       else
       {
          $menu = new CiscoIpPhoneText('Aktualisieren', 'FritzBox Login Fehlgeschlagen', 'Administrator: Bitte das FritzBox Passwort in der config.php.inc überprüfen');
          echo '<?xml version="1.0" encoding="utf-8" ?>';
          echo (string) $menu; 
          return;
       }
    }
    foreach(scandir("books") as $book){
        if(is_file("books/$book")){
            unlink("books/$book");
        }
    }
    do{
        curl_setopt($ch, CURLOPT_URL, $fritzCfg);
        curl_setopt($ch, CURLOPT_POSTFIELDS, array("sid" => $SID, "PhonebookId" => $telefonbuch, "PhonebookExportName" => "Telefonbuch", "PhonebookExport" => ""));
        $book = curl_exec($ch);
        $xml = simplexml_load_string($book);
	if(!$xml->phonebook)
            break;
        file_put_contents("books/$telefonbuch.xml",$book, LOCK_EX);
        $telefonbuch++;
    }while(true);
   curl_close($ch);

   header('Expires: ' . gmdate('D, d M Y H:i:s', time()-60*60) . ' GMT');
}

$has_books = false;
if(!isset($_GET["book"]))
{
    foreach(scandir("books") as $book){
        if(is_file("books/$book") && strpos($book,'.xml') !== false){
            $has_books=true;
        }
    }
    if($has_books){
        $menu = new CiscoIpPhoneMenu('Telefonbücher', 'Telefonbuch auswählen');
        foreach(scandir("books") as $book){
            if(is_file("books/$book") && strpos($book,'.xml') !== false){
               $input = file_get_contents("books/$book");
               $xml = simplexml_load_string($input);
               $attributes = $xml->phonebook->attributes();
               $name = $attributes["name"] . " (Fritzbox)";
               $get = $_GET;
               unset($get['refresh']);
               $url = "http://" . $_SERVER["SERVER_NAME"] . $_SERVER["PHP_SELF"] . '?' . http_build_query(array_merge($get,array("book"=>$book)));
               $menu->addMenuItem(new MenuItem($name, $url));
            }
        }
        $menu->addSoftKeyItem(new SoftKeyItem('Verlassen', 1, 'SoftKey:Exit'));
        $menu->addSoftKeyItem(new SoftKeyItem('Auswählen', 2, 'SoftKey:Select'));
        $url = 'http://' . $_SERVER['SERVER_NAME'] .  $_SERVER['PHP_SELF'] .  '?refresh';
        $menu->addSoftKeyItem(new SoftKeyItem('Aktualisieren', 4, $url));
    }
    else{
        $menu = new CiscoIpPhoneText('Telefonbücher', 'Keine Telefonbücher vorhanden', 'Es sind derzeit keine Telefonbücher vorhanden, durch "Aktualisieren" kann die FritzBox ausgelesen werden. Dies kann einige Sekunden dauern! Nach Änderungen im FritzBox-Telefonbuch muss erneut das Aktualisieren ausgeführt werden.');
        $menu->addSoftKeyItem(new SoftKeyItem('Zurück', 1, 'SoftKey:Back'));
        $url = 'http://' . $_SERVER['SERVER_NAME'] .  $_SERVER['PHP_SELF'] .  '?refresh';
        $menu->addSoftKeyItem(new SoftKeyItem('Aktualisieren', 4, $url));
        header('Expires: ' . gmdate('D, d M Y H:i:s', time()-60*60) . ' GMT');
    }
}
else{
    $input = file_get_contents("books/".$_GET["book"]);
    $xml = simplexml_load_string($input);

    if(isset($_GET["queryname"]) && strlen($_GET["queryname"])){
        for($i = count($xml->phonebook->contact)-1; $i >= 0; --$i){
            $name = $xml->phonebook->contact[$i]->person->realName;
            if(stripos($name, $_GET['queryname']) === false){
                $dom=dom_import_simplexml($xml->phonebook[0]->contact[$i]);
                $dom->parentNode->removeChild($dom);
            }
        }
    }
    if(isset($_GET["querynumber"]) && strlen($_GET["querynumber"])){
        for($i = count($xml->phonebook->contact)-1; $i >= 0; --$i){
            if($xml->phonebook->contact[$i]->telephony){
                $remove = true;
                for($j = count($xml->phonebook->contact[$i]->telephony->number)-1; $j >= 0; --$j){
                    $number =  preg_replace('/[^0-9+]/', '', $xml->phonebook->contact[$i]->telephony->number[$j]);
                    if(stripos($number, $_GET['querynumber']) !== false){
                        $remove = false;
                    }
                }
                if($remove){
                    $dom=dom_import_simplexml($xml->phonebook->contact[$i]);
                    $dom->parentNode->removeChild($dom);
                }
            }
        }
    }

    if(!isset($_GET["id"])){
        if(!isset($_GET["search"])){
            header('Expires: ' . gmdate('D, d M Y H:i:s', time()-60*60) . ' GMT');
            $offset = 0;
            if(isset($_GET["offset"])){
                $offset = (int) $_GET["offset"];
            }
            $attributes = $xml->phonebook->attributes();
            header('Expires: ' . gmdate('D, d M Y H:i:s', time()-60*60) . ' GMT');
           
            if(count($xml->phonebook->contact)>0){
                $menu = new CiscoIpPhoneMenu('Fritzbox Telefonbuch', $attributes['name']); 
                for ($i = $offset; $i < count($xml->phonebook->contact) && $i<$offset+30; ++$i){ 
                    $name = $xml->phonebook->contact[$i]->person->realName;
                    $url = "http://" . $_SERVER["SERVER_NAME"] .  $_SERVER["REQUEST_URI"] . "&" . "id=" . $i;
                    $menu->addMenuItem(new MenuItem($name, $url));
                }
                $menu->addSoftKeyItem(new SoftKeyItem('Zurück', 1, 'SoftKey:Exit'));
                $menu->addSoftKeyItem(new SoftKeyItem('Auswählen', 2, 'SoftKey:Select'));
                $url = "http://" . $_SERVER["SERVER_NAME"] . $_SERVER["PHP_SELF"] . '?' . http_build_query(array_merge($_GET,array("search"=>true)));
                $menu->addSoftKeyItem(new SoftKeyItem('Suche', 3, $url));
        
                if($offset>0){
                    $newoffset = $offset-30;
                    if($newoffset<0){
                        $newoffset=0;
                    }
                    $url = "http://" . $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"] . "&" . "offset=" .  $newoffset;
                    $menu->addSoftKeyItem(new SoftKeyItem('Vorherige Seite', 3, $url));
                }
                if($offset<count($xml->phonebook->contact)){
                    $url = "http://" . $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"] . "&" . "offset=" .  ($offset+30);
                    $menu->addSoftKeyItem(new SoftKeyItem('Nächste Seite', 4, $url));
                }
            }
            else{
                $menu = new CiscoIpPhoneText('Fritzbox Telefonbuch', 'Keine Einträge Vorhanden', 'Das Adressbuch ist leer, oder keine Einträge entsprechen den Suchkriterien');
                $menu->addSoftKeyItem(new SoftKeyItem('Zurück', 1, 'SoftKey:Back'));
            }
        }
        else{
            $get = $_GET;
            unset($get['search']);
            unset($get['queryname']);
            unset($get['querynumber']);
            $url = "http://" . $_SERVER["SERVER_NAME"] . $_SERVER["PHP_SELF"] . '?' . http_build_query($get);
            $menu = new CiscoIpPhoneInput('Fritzbox Telefonbuch', 'Bitte Namen oder Nummer eingeben', $url);
            if(isset($_GET['queryname'])){
                $queryname = $_GET['queryname'];
            }
            else{
                $queryname="";
            }
            if(isset($_GET['querynumber'])){
                $querynumber = $_GET['querynumber'];
            }
            else{
                $querynumber="";
            }

            $menu->addInputItem(new InputItem('Name:', 'queryname', InputFlags::U, $queryname));
            $menu->addInputItem(new InputItem('Nummer:', 'querynumber', InputFlags::T, $querynumber));
        }
    }
    else{
        $id = (int) $_GET["id"];
        $name = $xml->phonebook->contact[$id]->person->realName;
        if(strlen($name)>32){
            $name = substr($xml->phonebook->contact[$id]->person->realName,0,29) . "...";
        }
        if(!isset($_GET["details"])){
            $menu = new CiscoIpPhoneDirectory('Fritzbox Telefonbuch', $name);
            for ($i = 0; $i < count($xml->phonebook->contact[$id]->telephony->number); ++$i){
                $attributes = $xml->phonebook->contact[$id]->telephony->number[$i]->attributes();
                $number = preg_replace('/[^0-9+]/', '', $xml->phonebook->contact[$id]->telephony->number[$i]);
                $type = (string) $attributes["type"];
                $label = "Sonstige";
                if(array_key_exists($type, $translation)){
                    $label = $translation[$type];
                }
                else if(strstr($type,"label:")){
                    $label = substr($type,strlen("label:"));
                }
                $menu->addDirectoryEntry(new DirectoryEntry($label, $number));
            }
            $menu->addSoftKeyItem(new SoftKeyItem('Zurück', 1, 'SoftKey:Back'));
            $menu->addSoftKeyItem(new SoftKeyItem('Wählen', 2, 'SoftKey:Dial'));
            $url = "http://" . $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"] . "&" . "details";
            $menu->addSoftKeyItem(new SoftKeyItem('Details', 4, $url));
        }
        else{
            $text = 'Keine weiteren Informationen';
            if(count($xml->phonebook->contact[$id]->services->email)){
                $text = "Email:\n";
                for ($i = 0; $i < count($xml->phonebook->contact[$id]->services->email); ++$i){
                    $attributes = $xml->phonebook->contact[$id]->services->email[$i]->attributes();
                    $type = (string) $attributes["classifier"];
                    $label = "Sonstige";
                    if(array_key_exists($type, $translation)){
                        $label = $translation[$type];
                    }
                    else if(strstr($type,"label:")){
                        $label = substr($type,strlen("label:"));
                    }
                    $text.=$label . ': ';
                    $text.=$xml->phonebook->contact[$id]->services->email[$i]."\n";
                }
            }
            $menu = new CiscoIpPhoneText('Fritzbox Telefonbuch', $name, $text);
            $menu->addSoftKeyItem(new SoftKeyItem('Zurück', 1, 'SoftKey:Back'));
        }
    }
}
echo (string) $menu;
?>

