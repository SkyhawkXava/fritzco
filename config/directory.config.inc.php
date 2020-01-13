<?php
/*
 * @author Till Steinbach <till.steinbach@gmx.de>
 * @modified Christian Bartsch <cb AT dreinulldrei DOT de>
 * @copyright (c) Till Steinbach
 * @license GPL v2
 */

    $fritzbox_ip = 'fritz.box'; //Hier die IP oder den Hostname der FritzBox eintragen (meist fritz.box)
    // $fritzbox_user = "User"; // Benutzername, wenn in der Fritz!Box aktiviert.
	$fritzbox_password = 'yourpassword'; //Hier das Passwort eintragen
	
    
    $telefonbuch = 0; // Auswahl des erste Telefonbuchs, erstes Telefonbuch ist 0, nicht 1.
    
    $show_MissedCalls = true;
    $show_ReceivedCalls = true;
    $show_PlacedCalls = true;
	
	$show_BookSelection = false; // erlaubt keinen Rücksprung zur Telefonbuchauswahl (sinnvoll, wenn nur ein Telefonbuch vorhanden)
	$show_QuickDial = true;
	define ('QUICKDIAL_URL', 'http://192.168.178.33/cisco/quickdial.php'); // Pfad zu separatem Telefonbuch mit eigenen, festen internen Nummern.
	define ('QUICKDIAL_NAME', 'Kurzwahlen');
	
	$runon_Fritzbox = false; // Wenn Script direkt auf modifizierter FB eingesetzt wird, kann das erste Telefonbuch lokal kopiert werden
	define ('FRITZBOX_LOCAL_PATH', '/var/media/ftp/###STICKNAME###/###PATH###/0.xml'); // Pfad anpassen mit Stickname und Zielordner.
	 
?>
