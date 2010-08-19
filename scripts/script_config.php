<?php

require_once '../config_network.php';
global $network_id;

if($network_id == 0) {
$api_key = 'CHANGEME';
$secret  = 'CHANGEME';
$db_ip='CHANGEME';
$db_user = 'CHANGEME';
$db_pass = 'CHANGEME';
$db_name = 'CHANGEME';
$facebook_canvas_url='CHANGEME';
$base_url = 'CHANGEME';

}
else if($network_id == 1) {

$db_ip = 'CHANGEME'; 
$db_user = 'CHANGEME';
$db_pass = 'CHANGEME';
$db_name = 'CHANGEME';
$base_url = 'CHANGEME';
$facebook_canvas_url= $base_url;

}
