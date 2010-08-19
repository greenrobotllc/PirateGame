<?php

require_once 'includes.php';
global $DB;


//print_r($user);
$user_in_db = is_user_in_db($user);
if($user_in_db ==0) {
	$facebook->redirect("$facebook_canvas_url/install.php");
}

$to_user = $_REQUEST['to'];

$bomb_total = get_bomb_count($user);
$level = get_level($user);
//print "bomb is $bomb_total";
//print "gold is $gold";

//threw_bomb.php?sent=1&auth_token=85000cdeb643344d058a1ab7e85f842f
//check for existance of sent=1 and auth_token, and number of bombs, then increment users level
//and decrement the users bomb count
//take the gold from the $to and add it to the current user

$sent = $_REQUEST['sent'];
$auth_token = $_REQUEST['auth_token'];
if($sent != 1 || !isset($auth_token) || $bomb_total < 1) {
	$facebook->redirect("index.php");
}

else {
	$level_up = lower_level_up($user);
	
	set_bomb_total($user, $bomb_total - 1);
	
	$gold = get_coin_total($to_user);

	if($gold < 1) {
		$gold = rand(1,100);
	}
	else { // take away enemys coins
		set_coins($to_user, 0);
	}
	set_coins($user, get_coin_total($user) + $gold);
}



$to_id = $_REQUEST['to'];


//set user_was_bombed for other user
$sql = 'update users set user_was_bombed = ? where id = ?';
$DB->Execute($sql, array($user, $to_id));

//print_r($_REQUEST);
$facebook->redirect("index.php?msg=threw-bomb&gold=$gold&to=$to_user&level_up=$level_up");
//print $friend_selector_id;

?>

