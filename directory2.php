<?php
/*
 * @author Till Steinbach <till.steinbach@gmx.de>
 * @modified Christian Bartsch <cb AT dreinulldrei DOT de>
 * @copyright (c) Till Steinbach
 * @license GPL v2
 * @date 2013-11-17
 */


require_once __DIR__ . '/lib/cipxml/cipxml.php';


use fritzco\applications\DirectoriesApplication;


$start = microtime(true);

$app = new DirectoriesApplication();
$app->handle();


echo (string) $app;



echo "<!-- took: " . (microtime(true) - $start) . "seconds total-->";

?>

