<?php

namespace fritzco\plugins\fritz;

use fritzco\base\BaseDirectories;

class Directories extends BaseDirectories{
    private $fritzbox_ip;
    private $fritzbox_password;

    public static function getDirectories(){
        $directories = unserialize(file_get_contents("books/telefonbuch.xml"));
        return $directories;
    }
    
    function __construct($fritzbox_ip, $fritzbox_password) {
        $this->fritzbox_ip = $fritzbox_ip;
        $this->fritzbox_password = $fritzbox_password;
    }
    
    public function refreshCache(){
        $fritzbox_cfg = 'http://' . $this->fritzbox_ip . '/cgi-bin/firmwarecfg';
		$ch = curl_init('http://' . $this->fritzbox_ip . '/login_sid.lua');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$login = curl_exec($ch);
		$session_status_simplexml = simplexml_load_string($login);
		if ($session_status_simplexml->SID != '0000000000000000'){
			$SID = $session_status_simplexml->SID;
		} else {
			$challenge = $session_status_simplexml->Challenge;
			$response = $challenge . '-' . md5(mb_convert_encoding($challenge . '-' . $this->fritzbox_password, "UCS-2LE", "UTF-8"));
			curl_setopt($ch, CURLOPT_POSTFIELDS, "response={$response}&page=/login_sid.lua");
			$sendlogin = curl_exec($ch);
			$session_status_simplexml = simplexml_load_string($sendlogin);

			if ($session_status_simplexml->SID != '0000000000000000'){
				$SID = $session_status_simplexml->SID;
			} else {
				return;
			}
			$telefonbuch=0;
			do {
			    curl_setopt($ch, CURLOPT_URL, $fritzbox_cfg);
			    curl_setopt($ch, CURLOPT_POSTFIELDS, array("sid" => $SID, "PhonebookId" => $telefonbuch, "PhonebookExportName" => "Telefonbuch", "PhonebookExport" => ""));
			    $book = curl_exec($ch);
			    $xml = simplexml_load_string($book);
			    if(!$xml->phonebook) {
				    break;
			    }
			    else{
			        $directory = Directory::fromXML($xml, $telefonbuch);
			        $this->addDirectory($directory);
			    }
			    $telefonbuch++;
		    } while (true);
		
		curl_close($ch);
		}
		unlink("books/telefonbuch.xml");
		file_put_contents("books/telefonbuch.xml",serialize($this), LOCK_EX);
    }
}

?>
