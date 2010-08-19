<?php
require_once 'includes.php';

global $network_id;

if($network_id == 0) {
	require_once 'cant_pillage_yet_facebook.php'; 
}

else {
	require_once 'cant_pillage_yet_myspace.php'; 
}
?>
