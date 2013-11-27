<?php
/*
 * @author Till Steinbach <till.steinbach@gmx.de>
 * @modified Christian Bartsch <cb AT dreinulldrei DOT de>
 * @copyright (c) Till Steinbach
 * @license GPL v2
 * @date 2013-11-17
 */


require_once __DIR__ . '/lib/cipxml/cipxml.php';

use fritzco\plugins\fritz\Directories;

$start = microtime(true);

$directories = Directories::getDirectories();

echo "took: " . (microtime(true) - $start) . "seconds to deserialize";

if(isset($_GET["refresh"])){
    $directories->refreshCache();
}

print_r($directories);

echo "took: " . (microtime(true) - $start) . "seconds total";

?>

