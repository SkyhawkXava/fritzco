<?php
/*
 * @author Christian Bartsch <cb AT dreinulldrei DOT de>, bt43a
 * @copyright (c) Christian Bartsch, bt43a
 * @license GPL v2
 * @date 2013-12-06
 */
	$weather_apikey = 'xxxxxxx'; //GET YOUR OWN FREE API KEY AT http://www.openweathermap.org/
	$weather_city = 'Berlin';
	$weather_id = ; // can be omitted, unless there are several matches for your city by name
	$lang = 'de';
	$units = 'metric';
	$gmt_offset = 1; // adjust for your timezone
	$target = '7945'; // currently supports 7941, 7945 and 99xx (standard), affects graphics resolution
	$weather_refresh = 180; // refresh weather display after xx seconds
 
	$wallpaper_path = 'weather/wallpaper/';
	$wallpaper_file = 'XXXbyYYY_whatever.png'; // should match 498x289 for 99x, 289x168 or 298x156 for 79xx phones

?>
