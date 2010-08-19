<?php
global $network_id;

require_once 'config_network.php';
require_once '../client/facebook.php';
require_once 'lib.php';
require_once 'config.php';
global $network_id;
if($network_id == 1) {
	require_once 'myspace_login.php';
}
global $memcache;
?>