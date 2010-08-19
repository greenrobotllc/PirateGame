<?php
require_once 'includes.php';
global $DB, $network_id;

if($network_id == 1) {
	require_once 'throw_the_bomb_myspace_action.php';
	exit();
}
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


$bomb_on = get_bomb_on($to_id);
if($bomb_on == 0) {
	update_action($user, "bombs");
	$facebook->redirect("throw_bomb_which.php?msg=this-user-cant-be-bombed");

}

$app_friends = $facebook->api_client->friends_list;

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


	
	
    $facebook->redirect("index.php?msg=threw-bomb&gold=$gold&to=$to_user&level_up=$level_up");

}
else {



//print_r($_REQUEST);

	$body = "Arrrr! <fb:name uid=$user' /> threw a bomb at <fb:name uid='$to_id' />.  Join Pirates and throw your own.</a>";

$image_1 = "$base_url/images/flag_200.jpg";
$image_1_link = "$facebook_canvas_url/?i=$user";
$title = "threw a bomb..  Arrr..";


//for the user
try {

$re2 = $facebook->api_client->feed_publishActionOfUser($title, $body, $image_1, $image_1_link);
}
   catch (FacebookRestClientException $fb_e) {

      }
      
//for the other user (if we have the session key)


$sql = 'select session_key from users where id = ?';
$session_key = $DB->GetOne($sql, array($to_id));

global $facebook_canvas_url;

//notify whether they have the app or not
//$facebook->api_client->notifications_send(array($to_id) , "  threw a bomb at you! <a href='$facebook_canvas_url'>Play Pirates</a> and attack them back!", 'user_to_user');


if (!empty($session_key)) {
	$bombee = new Facebook($api_key, $secret);
    $bombee->api_client->session_key = $session_key;

	try {
		$title = 'had some coins stolen by a Pirate!';
		$result_infector = $bombee->api_client->feed_publishActionOfUser($title, $body, $image_1, $image_1_link);
		
		
    }
      catch (FacebookRestClientException $fb_e) {

      }
    }
    
$query = "INSERT INTO bombs_sent (from_id, to_id, created_at) VALUES ('". $user . "', '" . $to_id . "', now())";
		$DB->Execute($query);
	
	
/* if (empty($session_key)) {
		
	$to = $to_id;
	//$vorw = get_type_for($user);
	$title = "Bomb"; //get_type_name_for($user);
	$content = "<fb:req-choice url=\"$facebook_canvas_url/install.php?i=" . $user . "\" label=\"Arrrrr!\" /> Avast! <fb:name firstnameonly='true' uid='$user'/> threw a bomb at <fb:name firtname='true' uid='$to_id'/>! Attack <fb:pronoun uid='$user' useyou='false' objective='true' /> back!";
	


	$request = "request";
	$image = "$base_url/images/flag_200.jpg";
	


	$confirmUrl = $facebook->api_client->notifications_sendRequest ($to, $title, $content, $image, $request);
	if($confirmUrl != "") {
		$facebook->redirect($confirmUrl . "&next=threw_bomb.php?to=$to");
	}
	else {
		$facebook->redirect("$facebook_canvas_url/index.php?msg=send-limit-bombs");
	}
	
}
else {
	$facebook->redirect("$facebook_canvas_url/threw_bomb.php?to=$to_id&sent=1&auth_token=85000cdeb643344d058a1ab7e85f842f");
}
*/









//$to_user = $_REQUEST['to'];

$bomb_total = get_bomb_count($user);
$level = get_level($user);
//print "bomb is $bomb_total";
//print "gold is $gold";


//Array ( [to] => 1807687 [sent] => 1 [auth_token] => c863cb0eff0a9f3147039a8494fb4bd5 [fb_sig_in_canvas] => 1 [fb_sig_time] => 1185710443.6059 [fb_sig_user] => 691816855 [fb_sig_profile_update_time] => 1182469142 [fb_sig_session_key] => dfa6969803eede11ffaf7e2b-691816855 [fb_sig_expires] => 0 [fb_sig_friends] => 403601,1807687,657909358,690907236,697396582,700249135,715005094,744942428 [fb_sig_api_key] => 2cec64d22091dfc9ff0ccf9a0804057d [fb_sig_added] => 1 [fb_sig] => 4c28daf5f0d1eee831dcd58f50f61672 )


//threw_bomb.php?sent=1&auth_token=85000cdeb643344d058a1ab7e85f842f
//check for existance of sent=1 and auth_token, and number of bombs, then increment users level
//and decrement the users bomb count
//take the gold from the $to and add it to the current user

$sent = $_REQUEST['sent'];
$auth_token = $_REQUEST['auth_token'];
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

}



//$to_id = $_REQUEST['to'];
//echo "gold is $gold";

//set user_was_bombed for other user
$sql = 'update users set user_was_bombed = ? where id = ?';
$DB->Execute($sql, array($user, $to_id));

//print_r($_REQUEST);
$facebook->redirect("index.php?msg=threw-bomb&gold=$gold&to=$to_user&level_up=$level_up");
//print $friend_selector_id;

}



 ?>
