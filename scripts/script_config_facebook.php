<?php

include_once '../config_network.php';

global $network_id;

if($network_id == 0) {
	require_once 'script_config_facebook_real.php';
}
else if($network_id == 1) {
	require_once 'script_config_myspace.php';
}