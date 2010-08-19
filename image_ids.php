<?php
global $network_id;

if($network_id == 0) {
	require_once 'image_ids_facebook.php';
}
else if($network_id == 1) {
	require_once 'image_ids_myspace.php';
}
