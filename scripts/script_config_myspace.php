<?php

include_once '../../client/facebook.php';
include_once '../memcache_wrapper.php';
//PRODUCTION
$api_key = 'CHANGEME';
$secret  = 'CHANGEME';

$db_ip='CHANGEME';

//PRODUCTION
$db_user = 'CHANGEME';
$db_pass = 'CHANGEME';

$db_name = 'CHANGEME';


$facebook_canvas_url='http://dev.greenrobot.com/pirates/myspace';
$base_url = $facebook_canvas_url;

$memcache_temp = new MemcacheWrapper('mt'); //use temp for mile limits, gambing limits
$memcache_temp->addServer('10.12.198.194', 11213);
//$memcache_temp->addServer('10.8.50.196', 11213);

$memcache = new MemcacheWrapper('my');

    $memcache->addServer('10.12.198.194', 11211);


////////////////////////////////////////////////////////////////////////////////////////////

//be sure to have this set to true so we don't try and include the other config.php in libxml_clear_errors()
$script_config = true;

require_once '../image_ids.php';
require_once '../lib.php';
