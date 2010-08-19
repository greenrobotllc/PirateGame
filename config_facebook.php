<?php

//for dev tree
//$memcache = memcache_connect('localhost', 11211);


//PRODUCTION
$api_key = 'CHANGEME';
$secret  = 'CHANGEME';

global $network_id;
$network_id = 0;

$db_ip='CHANGEME';

$facebook_canvas_url='CHANGEME';
$base_url = 'CHANGEME';


//$base_url = 'http://piratewars.dyndns.org/piratewars';

////////////////////////////////////////////////////////////////////////////////////////////
//memcache stuff and facebook header


$facebook = new Facebook($api_key, $secret);

//$user = $facebook->get_loggedin_user();
//print_r($user);
//echo $user;
//die("ok");
//$is_logged_out = !$user;

$i = $_REQUEST['i'];

//if($is_logged_out && !isset($i)) {
//	require_once 'index_logged_out.php';
//	 exit;
//}

$facebook->require_frame();
$user = $facebook->require_login();


////////////////////////////////////////////////////////////////////////////////////////////



require_once 'cheater_ids.php';

///ISOLATED SERVER FOR CHEATERS
if(isset($cheaters[$user])) {
//if(in_array($user, $cheaters)) {
    die("Your account has been banned from Pirates.  This is because you broke the Facebook Terms of Service or the Pirate Rules.  Common reasons for being banned are using automated bots to play the game or creating fake/multiple Facebook accounts. If you have any questions please message me.  I apoligize for not messaging you individually. -Andy");
 



}
else {
    $db_user = 'CHANGEME';
    //$db_user = 'CHANGEME';
    //$db_pass = 'CHANGEME';
    $db_pass = 'CHANGEME';

    // the name of the database that you create for footprints.
    //$db_name = 'piratewarsdev';
    $db_name = 'CHANGEME';

    
    $memcache_temp = new Memcache(); //use temp for mile limits, gambing limits
    $memcache_temp->addServer('10.12.198.194', 11219);

    $memcache = new Memcache();
    $memcache->addServer('10.12.198.194', 11211);


}

require_once 'image_ids.php';
