<?php
global $facebook, $network_id;
 
$network_id = 1;

//$db_ip = '10.8.50.198';
$db_ip='CHANGEME'
 	
$db_user = 'CHANGEME';
$db_pass = 'CHANGEME';
$db_name = 'CHANGEME';

$image_url = 'CHANGEME/images/a';


//echo "config myspace";

$facebook = new Facebook();


//PRODUCTION
$base_url = 'http://dev.greenrobot.com/pirates/myspace';
$facebook_canvas_url= $base_url;

//$base_url = 'http://piratewars.dyndns.org/piratewars';

////////////////////////////////////////////////////////////////////////////////////////////
//memcache stuff and facebook header

require_once 'memcache_wrapper.php';

    $memcache_temp = new MemcacheWrapper('mt'); //use temp for mile limits, gambing limits
    $memcache_temp->addServer('10.12.198.194', 11213);
    $memcache_temp->addServer('10.8.50.196', 11213);

    $memcache = new MemcacheWrapper('my');
    $memcache->addServer('10.12.198.194', 11211);





require_once 'image_ids.php';
	//require_once('Space.php');

global $user, $key, $secret;

  	//$key = 'http://www.greenrobot.com/pirates/myspace';
  	//$secret = 'CHANGEME';
  	
	//$user = $_REQUEST['opensocial_owner_id'];



global $query_string;
//print "<pre>";
//$oauth_params = array();
//$exclude = array('action', 'image_id', 'splash', 'failure', 'success', 'bet', 'u', 'type', 'c', 'msg', 'to', 'gold', 'level_up', 'booty', 'amount', 'd');

/*
foreach($_REQUEST as $x => $y) {
	//print "x:    ";
	//print_r($x);
	//print "y: ";
	//print_r($y);
	
	if(!in_array($x, $exclude)) {
		$oauth_params[$x] = $y;
	}
	
}
*/
//print "</pre>";

//$query_string = http_build_query($oauth_params);
//print_r($query_string);
