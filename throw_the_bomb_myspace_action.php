<?php
require_once 'includes.php';
global $DB, $network_id;

if($network_id == 0) {
	require_once 'throw_the_bomb_action.php';
	exit();
}

$enemy_name = $_REQUEST['enemy_name'];

redirect_to_index_if_not($user, "bomb");

update_action($user, "NULL");

$to_id = $_REQUEST['friend_selector_id'];
$to_user = $to_id;
if(!isset($to_id) || empty($to_id)) {
	$facebook->redirect("index.php");
	//echo 'should be redirecting 1';
}


//check if the bombing limit has been reached
$bomb_amount = $DB->GetOne('select amount from bombing_limits where from_id = ? and to_id = ?', array($user, $to_id));
if($bomb_amount > 100000) {
	$facebook->redirect("index.php?cant-bomb-user-over-limit"); 
}

//$app_friends = $facebook->api_client->friends_list;
$app_friends = get_friend_ids($user);

$is_friend = false;
foreach ($app_friends as $value)
{
	if($value == $to_user) {
		$is_friend = true;
	}
}
if($is_friend == false) {
    //DONT REALLY THROW BOMB AND LIMIT COINS TO 100
	update_action($user, "NULL");
	
	
	$bomb_total = get_bomb_count($user);
	if($bomb_total < 1) {
	   $facebook->redirect("index.php");
	   //echo 'should be redirecting 2';
    }

    else {
	   $level_up = lower_level_up($user);
	   if($level_up) {
		  log_levels($user, 'bombing', $to_user);
	   }
	
	   set_bomb_total($user, $bomb_total - 1);
	
	   //$gold = get_coin_total($to_user);

	   //if($gold < 1) {
	       	$gold = rand(1,100);
	   //}
	   //else { // take away enemys coins
		 // set_coins($to_user, 0);
	      //log_coins($to_user, -$gold, 'lost coins from bomb private user', $user);
	  // }

	
	   //increment the bombing limit
	   //$amount = get_coin_total($to_user);
	   $DB->Execute('insert into bombing_limits (from_id, to_id, amount) values(?, ?, ?) on duplicate key update amount = amount+?', array($user, $to_user, $gold, $gold));

	   set_coins($user, get_coin_total($user) + $gold);
	   log_coins($user, $gold, 'gained coins from bomb sent to anon user', $to_user);

    }


	
	
    $facebook->redirect("index.php?msg=threw-bomb&gold=$gold&to=$to_user&level_up=$level_up&enemy_name=$enemy_name");

}
else {


    
$query = "INSERT INTO bombs_sent (from_id, to_id, created_at) VALUES ('". $user . "', '" . $to_id . "', now())";
		$DB->Execute($query);






//$to_user = $_REQUEST['to'];

$bomb_total = get_bomb_count($user);
$level = get_level($user);
//print "bomb is $bomb_total";
//print "gold is $gold";


	$level_up = lower_level_up($user);
	if($level_up) {
		log_levels($user, 'bombing', $to_user);
	}
	
	set_bomb_total($user, $bomb_total - 1);
	
	$gold = get_coin_total($to_user);

	//do this
	if($gold > 100000) {
		$gold = 100000;
	}

	//and this if/else
	if($gold < 1) {
		$gold = rand(1,100);
	}
	else { // take away enemys coins
		set_coins($to_user, get_coin_total($to_user) -$gold);
		log_coins($to_user, -$gold, 'lost coins from bomb', $user);
	}


	   //increment the bombing limit
	   $DB->Execute('insert into bombing_limits (from_id, to_id, amount) values(?, ?, ?) on duplicate key update amount = amount+?', array($user, $to_user, $gold, $gold));

	set_coins($user, get_coin_total($user) + $gold);
	log_coins($user, $gold, 'gained coins from bomb', $to_user);





//$to_id = $_REQUEST['to'];
//echo "gold is $gold";

//set user_was_bombed for other user
$sql = 'update users set user_was_bombed = ? where id = ?';
$DB->Execute($sql, array($user, $to_id));

//print_r($_REQUEST);
$facebook->redirect("index.php?msg=threw-bomb&gold=$gold&to=$to_user&level_up=$level_up&enemy_name=$enemy_name");
//print $friend_selector_id;

}



 ?>
