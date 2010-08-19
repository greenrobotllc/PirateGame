<?php
global $network_id;

//print_r("network id in config: $network_id");

if($network_id == 0) {
	require_once 'config_facebook.php';
}
else if($network_id == 1) {
	require_once 'config_myspace.php';
}
