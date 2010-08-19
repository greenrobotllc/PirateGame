<?php

include_once '../../client/facebook.php';

//PRODUCTION
$api_key = 'CHANGEME';
$secret  = 'CHANGEME';

$db_ip='CHANGEME';
$db_user = 'CHANGEME';
$db_pass = 'CHANGEME';
$db_name = 'CHANGEME';


$facebook_canvas_url='CHANGEME';
$base_url = 'CHANGEME';


//andys facebook id;
//$id = 1807687;

//brians production session key for pirates
$session_key = "CHANGEME";

$facebook = new Facebook($api_key, $secret);
$facebook->set_user($id, $session_key);


////////////////////////////////////////////////////////////////////////////////////////////
//memcache stuff

$memcache_temp = new Memcache(); //use temp for mile limits, gambing limits
$memcache_temp->addServer('10.12.198.194', 11219);

$memcache = new Memcache();
$memcache->addServer('10.12.198.194', 11211);

////////////////////////////////////////////////////////////////////////////////////////////

//be sure to have this set to true so we don't try and include the other config.php in libxml_clear_errors()
$script_config = true;

require_once '../image_ids.php';
require_once '../lib.php';
