<?php
require_once 'includes.php';

global $DB;
//require_once 'header.php';
redirect_to_index_if_not($user, "left_dynamite");

$type = get_team($user);

update_action($user, "NULL");

//print_r($user);
$user_in_db = is_user_in_db($user);
if($user_in_db ==0) {
	$facebook->redirect("$facebook_canvas_url/install.php");
}

$to_user = $_REQUEST['to'];

$dynamite_total = get_dynamite_count($user);
$monkey_total = get_monkey_count($user);

$level = get_level($user);


//print "bomb is $bomb_total";
//print "gold is $gold";


//threw_dynamite.php?sent=1&auth_token=85000cdeb643344d058a1ab7e85f842f
//check for existance of sent=1 and auth_token, and number of bombs, then increment users level
//and decrement the users bomb count
//take the gold from the $to and add it to the current user

$sent = $_REQUEST['sent'];
//$auth_token = $_REQUEST['auth_token'];


if($dynamite_total < 1 || $monkey_total < 1 || !$sent) {
	$facebook->redirect("index.php");
}


else {
	
	set_dynamite_total($user, $dynamite_total - 1);
	set_monkey_total($user, $monkey_total - 1);
	
	$gold = get_coin_total($to_user);

	if($gold < 1) {
		$gold = rand(1,50);
	}
	
	if($gold > 50) {
		$gold = 50;
	}
	
	$to_id_team = get_team($to_user);

	if(empty($to_id_team)) {
		$level_up = lower_level_up($user);
		if($level_up) {
			log_levels($user, 'dynamite level up', $to_user);
		}
	        
		$gold = rand(1,50);

 
		set_coins($user, get_coin_total($user) + $gold); //landlubber give em coins now

		log_coins($user, $gold, 'dynamited landlubber', $to_user);

		$facebook->redirect("index.php?msg=left-dynamite&gold=$gold&to=$to_user&level_up=$level_up");
	
	}
else {
	//pirate, wait for dynamite to blow up
	$facebook->redirect("index.php?msg=left-dynamite-for-pirate&to=$to_user");

}


}


//$to_id = $_REQUEST['to'];



//print_r($_REQUEST);

$facebook->redirect("index.php?msg=left-dynamite&gold=$gold&to=$to_user&level_up=$level_up");

//print $friend_selector_id;

?>

