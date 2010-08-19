<?php
global $DB;
//memcache map
//$user_id:t -- maps to users team
//$user_id:a -- maps to users action
//$user_id:z -- maps to whether to show ad or not
//$user_id:m -- maps to miles
//$user_id:j -- maps to whether the user was attacked
//$user_id:l -- maps to users level
//$user_id:d -- maps to users damage
//$user_id:b -- maps to whether user was bombed
//$user_id:c -- maps to captcha correct result
//$user_id:f -- maps to captcha fail count

error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
error_reporting(6135);
//session_start();
//id, user_id, comment, created_at, xid
global $memcache;
global $memcache_temp;

//if using script config, then don't include config.php
if($script_config != true) {
	require_once("config.php");
}
require_once("adodb/adodb-exceptions.inc.php");
require_once("adodb/adodb.inc.php");
//include_once '../client/facebook.php';
//require_once(dirname(__FILE__)."/../client/facebook.php");
//$facebook = new Facebook($api_key, $secret);
$connect = "mysql://$db_user:$db_pass@$db_ip/$db_name?persist";
$DB = NewADOConnection($connect);

$connect = "mysql://$db_user:$db_pass@$db_ip/$db_name";
$DBNoPersist = NewADOConnection($connect);

function get_postcount($key) {
	global $DB;
	return $DB->GetOne('select count(*) from comments where xid = ?', array($key));
}

function get_last_10_posts($key) {
	global $DB;
	return $DB->GetArray('select * from comments where xid = ? order by created_at desc limit 10', array($key));
}

function get_square_profile_pic($profile_id) {
	global $network_id;
	if($network_id == 0) {
		return "<fb:profile-pic uid='$profile_id' size='square' linked='true' />";
	}
	else {
	 	
	 	try {
		 	require_once('Space.php');
  			$key = 'CHANGEME';
  			$secret = 'CHANGEME';
			$s = new Space($key, $secret);
			$hProfile = $s->profile($profile_id);
			$imageuri = $hProfile['imageuri'];
			return "<img src='$imageuri' >";
		}
		catch(Exception $e) {
			//some problem talking to myspace to get pic
			return;
		}
	}
}


function get_square_profile_pic_50($profile_id) {
	global $network_id;
	if($network_id == 0) {
		return "<fb:profile-pic uid='$profile_id' size='square' linked='true' />";
	}
	else {
	 	
	 	try {
		 	require_once('Space.php');
  			$key = 'CHANGEME';
  			$secret = 'CHANGEME';
			$s = new Space($key, $secret);
			$hProfile = $s->profile($profile_id);
			$imageuri = $hProfile['imageuri'];
			return "<img src='$imageuri' width='50'>";
		}
		catch(Exception $e) {
			//some problem talking to myspace to get pic
			return;
		}
	}
}

function get_friends($user) {
	require_once('Space.php');
  	$key = 'CHANGEME';
  	$secret = 'CHANGEME';
	$s = new Space($key, $secret);
	$hProfile = $s->friends($user);
	return $hProfile['friends'];
}

function get_friend_ids($user) {
	$friends = get_friends($user);
	$ids = array();
	foreach($friends as $friend) {
		$ids[] = $friend['userid']; 
	}
	return $ids;
}

function href($link, $params = NULL) {
	global $network_id,  $query_string;
	if(true) {
		echo "href = '" . $link . $params . "'";
	}
	else	{
		if($params != NULL) {
			echo "href = '" . $link  . $params .  "'";
		
		}
		else {
			echo "href = '$link";
		}
	
	}
}


function href_return($link, $params = NULL) {
	global $network_id,  $query_string;
	if(true) {
		return "href = '$link'";
	}
	else	{
		if($params != NULL) {
			return "href = '" . $link  . $params . "'";
		
		}
		else {
			return "href = '$link'";
		}
	
	}
}

function image($image_name) {
	global $network_id, $image_uid, $image_url;
	//temporary hopefully
	//$network_id = 1;
	//$image_url = 'CHANGEME/images/a';
	//echo "<img border='0' src='$image_url/$image_name' />";
	//return;
	///*
	if($network_id == 0) {
		echo "<fb:photo pid='$image_name' uid=\"$image_uid\" />";
	}
	else if($network_id == 1 || $network_id == 2) {
		echo "<img border='0' src='$image_url/$image_name' />";

	}
	//*/
	

}

function image_return($image_name) {
	global $network_id, $image_uid, $image_url;
	//temporary hopefully
	//$network_id = 1;
	//$image_url = 'CHANGEME/images/a';
	//return "<img src='$image_url/$image_name' />";
	///*
	if($network_id == 0) {
		return "<fb:photo pid='$image_name' uid=\"$image_uid\" />";
	}
	else if($network_id == 1 || $network_id == 2) {
		return "<img src='$image_url/$image_name' />";

	}
	//*/
}


function get_race_time_left() {
	global $DB;
	$sql = "select created_at as a, now() as b from race_results order by created_at desc";
	$r = $DB->GetRow($sql);
	
	//print_r($r);
	
	//YYYYMMDDHHMMSS
	$a = $r['a'];
	
	$b = $r['b'];
	
	$c = date("YmdHis", strtotime($a));
	$d = date("YmdHis", strtotime($b));
	//print $created_at;
	//print_r($a);
	//print_r($b);
	$s = 1800 + strtotime($a) - strtotime($b);
	//print $s;
	//return relative_date($c, $d);
	$m =round( $s /60 );
	if($m == 1) {
		return '1 minute';
	}
	else {
		return $m . ' minutes';
	}

}

function get_consecutive_merchant_trades($user) {
	return 0;
}
            	
function get_merchant_trade($user) {
	global $memcache;
	
	//try and get trade out of memcache
	$mt = $memcache->get($user . 'mtrade');
	if($mt != false) {
		return $mt;
	}
	
	$pvp_toggle = $memcache->get($user . 'pvp');
	if($pvp_toggle == 'off') {
		//echo "pvp off!";
		$gold_amount = rand(25,100);
	}
	else {
		$gold_amount = 0;
	}
	global $DB;
	$consecutive_trades = $DB->GetOne('select consecutive_merchant_trades from users where id = ?', array($user));

	$amount1 = rand(2,5);
  $amount2 = rand(2,5);

	if($consecutive_trades > rand(3,5) && $pvp_toggle != 'off') {
		$amount2 = round($amount2 * rand(1.5,2.5));
	}

	if($consecutive_trades > rand(10,15) && $pvp_toggle != 'off') {
		$amount2 = $amount2 * rand(5,10);
		$DB->Execute('update users set consecutive_merchant_trades = 0 where id = ?', array($user));
	}	
	
  $stuffid1 = rand(1,13);
  $stuffid2 = rand(1,13);
  if($stuffid2 == $stuffid1) {
  	$stuffid2 = $stuffid1 + 1;
  }
  if($stuffid2 == 14) {
  	$stuffid2 = 1;
  }
  $stuff1 = get_booty_data_from_id($stuffid1);
  $stuff2 = get_booty_data_from_id($stuffid2);
            		            		
  if( $stuff1['0'] == 'Dynamite' ||  $stuff1['0'] == 'Gold Bars' || $stuff1['0'] == 'Rum') {
  	$what_you_give_name = $stuff1['0'];
  }
  else if ($stuff1['0'] == 'Message in a Bottle'){
  	$what_you_give_name = 'Messages in Bottles';
  }
  else {
		$what_you_give_name = $stuff1['0'] . 's';
	}
	
	if( $stuff2['0'] == 'Dynamite' ||  $stuff2['0'] == 'Gold Bars' || $stuff2['0'] == 'Rum') {
		$what_you_get_name = $stuff2['0'];
	}
  else if ($stuff2['0'] == 'Message in a Bottle'){
  	$what_you_get_name = 'Messages in Bottles';
  }
  else {
		$what_you_get_name = $stuff2['0'] . 's';	
 	}
  
  $mt = array(
		'what_you_give_name'=> $what_you_give_name, 
    'what_you_get_name' => $what_you_get_name, 
    'what_you_give_amount' => $amount1, 
    'what_you_get_amount' => $amount2, 
    'what_you_give_pic' => $stuff1['3'], 
    'what_you_get_pic' => $stuff2['3'], 
  	'what_you_give_id' => $stuffid1, 
    'what_you_get_id' => $stuffid2, 
    'gold_amount' => $gold_amount);

	$memcache->set($user . 'mtrade', $mt);
  
  return $mt;    
}

function get_number_entries($animal_char) {
	global $DB;
	$sql = "select count(*) from races where type = '$animal_char' and completed = 0";
	$r = $DB->GetOne($sql);
	return $r;
}				

function  get_past_winner_amount($a) {
	if($a == 'parrot') {
		return 5000;
	}
	else {
		return 600;
	}	
}

function not_enough_to_race($user, $animal_char) {
	//now check if they have enough parrotrs or monkeys
		if($animal_char == 'p') {
		$animal_count = get_parrot_count($user);
		}
		else {	
			$animal_count = get_monkey_count($user);
		}
		
		//print_r($animal_count);
		
		if($animal_count < 1) {
			return true;
		}
		
		return false;
}
function user_can_enter_race($user, $animal_char) {
	global $DB;
	$sql = "select count(*) from races where user_id = $user and type = '$animal_char' and completed = 0";
	$r = $DB->GetOne($sql);
	if($r == 0) {	
		return true;
		
	} 
	
	else {
		return false;
	}
}

function  get_past_winner_id($a) {
	if($a == 'parrot') {
		return 3423444;
	}
	else {
		return 1807681;
	}	
}
	
			
function get_jackpot_amount($a) {
	if($a == 'p') {
		$entries = get_number_entries($a);
		$jackpot = $entries * 200;
		if($jackpot < 5000) {
			$jackpot = 5000;
		}
	}
	else {
		$entries = get_number_entries($a);
		$jackpot = $entries * 50;
		if($jackpot < 2500) {
			$jackpot = 2500;
		}
	}
	
	return $jackpot;
}

function get_races_won_by_id($user) {
	global $DB;
	$sql = "select type, jackpot, created_at, entrants from race_results where user_id = $user order by created_at DESC";
	$results_array = $DB->GetArray($sql);
	
	return $results_array;
}


function user_eligible_for_offer($user, $offer_id) {

	global $DB;
	$r = $DB->GetOne('select count(*) from offers where user_id = ? and offer_id = ?', array($user, $offer_id));
	
	//print $r;
	return !$r;
	
}


//moderators and banned users on the discussion board
function get_moderators() {
	return array(1807687);
}

function get_no_captcha() {
	return array();

}

function get_banned() {
	return array();
}

function get_audited_users() {
	global $DB, $memcache;
	require_once 'cheater_ids.php';
	//todo don't hardcode ids in teh query so when new ones are added...
	global $cheaters;
	if(false) {
		$n = $memcache->get('audied_users');
		if($n == FALSE) {
			$r =$DB->GetArray('select logged_users.id, level from logged_users, users where users.id = logged_users.id and logged_users.id not in () order by users.name');
			$n = array();
			foreach($r as $key => $value) {
				$n[]=$value['id'];
			}
			
			$memcache->set('audited_users', $n, false, 3600);
		}
	}
	else {
		//$r =$DB->GetArray('select logged_users.id, users.level from logged_users, users where logged_users.id = users.id order by users.name;');
		$r =$DB->GetArray('select logged_users.id, level from logged_users, users where users.id = logged_users.id and logged_users.id not in () order by users.name');
			

	}

	return $r;
	
}


function get_audited_users_ids() {
	global $DB, $memcache;
	require_once 'cheater_ids.php';
	//todo don't hardcode ids in teh query so when new ones are added...
	global $cheaters;
	if($memcache) {
		$n = $memcache->get('audited_users_ids');
		if($n == FALSE) {
			$r =$DB->GetArray('select id from logged_users');
			$n = array();
			foreach($r as $key => $value) {
				$n[]=$value['id'];
			}
			
			$memcache->set('audited_users_ids', $n, false, 3600);
		}
	}
	else {
		$r =$DB->GetArray('select id from logged_users');
	
		$n = array();
		foreach($r as $key => $value) {
			$n[]=$value['id'];
		}
	}

	return $n;
	
}


function dashboard($show_make_better = FALSE) {
	global $network_id;
	
	if($network_id == 1) {
		include 'style.php';
	}

	$output = "<table><tr><td width=\"620\"><div class=\"dashboard_header\"><div class=\"dh_links clearfix\">";
	$output .= "<a " . href_return('index.php') . "\">Go Sailin'</a>";
	$output .= "<span class=\"pipe\">|</span>";
	$output .= "<a " . href_return('recruit.php') . "\">Recruit</a>";
	$output .= "<span class=\"pipe\">|</span>";
	$output .= "<a " . href_return('user_profile.php') . ">Stats</a>";
	$output .= "<span class=\"pipe\">|</span>";
	$output .= "<a " . href_return('user_profile.php?action=inventory') . "\">Booty</a>";
	$output .= "<span class=\"pipe\">|</span>";
	$output .= "<a " . href_return('leaderboard.php') . "\">Leaderboard</a>";
	$output .= "<span class=\"pipe\">|</span>";
	$output .= "<a " . href_return('user_profile.php?action=fellow_pirates') . "\">My Mates</a>";
	
	if($network_id == 0) {
		$output .= "<span class=\"pipe\">|</span>";
		$output .= "<a href=\"surveys.php\">Free Booty</a>";
		$output .= "<span class=\"pipe\">|</span>";
		$output .= "<a " . href_return('settings.php') . "\">Settings</a>";
	}
	
	
	if($network_id == 0) {
	$output .= "<span class=\"pipe\">|</span>";
	$output .= "<a href=\"http://www.facebook.com/apps/application.php?api_key=CHANGEME\">About</a>";
	}
	
	if($network_id == 1) {
	$output .= "<span class=\"pipe\">|</span>";
	$output .= "<a target='_blank' href=\"http://www.myspace.com/piratewars\">About</a>";
	}	
	$output .= "</div></div></td>";
	$output .= "</tr></table><br>";

	return $output;
}

function success_msg($msg) {
global $network_id;
if($msg == '') {
	return;
}
if($network_id == 0) 
{
?>
<center>
<fb:success>
     <fb:message><?php echo $msg; ?></fb:message>
</fb:success>
</center>

<?php
}

else {

?>
<center>
<div style='width:650px; text-align:center'>
<div class="standard_message has_padding"><h1 class="status"><?php echo $msg; ?></h1>
 </div>
 </div>
 </center>
 <?php
}
}

function success_msg_return($msg) {
	global $network_id;
	if($network_id == 0) 
	{
		$str = "<center><fb:success><fb:message>$msg</fb:message></fb:sucess></center>";
	}
	else {
		$str = "<center><div style='width:650px; text-align:center'><div class='standard_message has_padding'><h1 class='status'>$msg</h1></div></div></center>";
	}
	
	return $str;
}


function explanation_msg($msg) {
global $network_id;
if($network_id == 0) 
{
?>
<fb:explanation>
     <fb:message><?php echo $msg; ?></fb:message>
</fb:explanation>


<div style='width:650px'>
<div class="standard_message has_padding">
<h1 class="explanation_note">
<?php echo $msg; ?></h1>
</div>
</div>

<?php
}

else {

?>

<div style='width:650px'>
<div class="standard_message has_padding">
<h1 class="explanation_note">
<?php echo $msg; ?></h1>
</div>
</div>

 <?php
}
}


function explanation_msg_return($msg) {
	global $network_id;
	if($network_id == 0) 
	{
		$str = "<center><fb:explanation><fb:message>$msg</fb:message></fb:explanation></center>";
	}
	else {
		$str = "<center><div style='width:650px; text-align:center'><div class='standard_message has_padding'><h1 class='explanation_note'>$msg</h1></div></div></center>";
	}
	
	return $str;
}



function error_msg($msg) {
global $network_id;
if($network_id == 0) 
{
?>
<fb:error>
     <fb:message><?php echo $msg; ?></fb:message>
</fb:error>


<?php
}

else {

?>
<div style='width:650px'>

<div class="standard_message has_padding">
<h1 id="error"><?php echo $msg; ?></h1></div>

 </div>
 </div>
 
 <?php
}
}



function error_msg_return($msg) {
	global $network_id;
	if($network_id == 0) 
	{
		$str = "<center><fb:error><fb:message>$msg</fb:message></fb:error></center>";
	}
	else {
		$str = "<center><div style='width:650px; text-align:center'><div class='standard_message has_padding'><h1 id='error'>$msg</h1></div></div></center>";
	}
	
	return $str;
}



function dashboard_loggedout() {
	$output = "<table><tr><td width=\"620\"><div class=\"dashboard_header\"><div class=\"dh_links clearfix\">";
	$output .= "<a href=\"index.php\">Go Sailin'</a>";
	$output .= "<span class=\"pipe\">|</span>";
	$output .= "<a href=\"playnow.php\">Recruit</a>";
	$output .= "<span class=\"pipe\">|</span>";
	$output .= "<a href=\"playnow.php\">Stats</a>";
	$output .= "<span class=\"pipe\">|</span>";
	$output .= "<a href=\"playnow.php?action=inventory\">Booty</a>";
	$output .= "<span class=\"pipe\">|</span>";
	$output .= "<a href=\"playnow.php\">Leaderboard</a>";
	$output .= "<span class=\"pipe\">|</span>";
	$output .= "<a href=\"playnow.php?action=fellow_pirates\">My Mates</a>";
	$output .= "<span class=\"pipe\">|</span>";
	$output .= "<a href=\"playnow.php\">Free Booty</a>";
	$output .= "<span class=\"pipe\">|</span>";
	$output .= "<a href=\"playnow.php\">Settings</a>";
	$output .= "<span class=\"pipe\">|</span>";
	$output .= "<a href=\"http://www.facebook.com/apps/application.php?api_key=CHANGEME\">About</a>";
	$output .= "</div></div></td>";
	$output .= "</tr></table><br>";

	return $output;
}


function get_num_ads_complete($user) {
	global $DB;
	
	$sql = "SELECT offer_id FROM offers where user_id = $user";
	$value = $DB->GetArray($sql);
	return 1;
	return count($value);
}

function get_total_user_count() {
	global $memcache, $DB;
	$r = $memcache->get("total_user_count");
	if($r == FALSE) {
		$sql = "SELECT max(auto_id) from users"; 
		$r = $DB->GetOne($sql);
		$memcache->set("total_user_count", $r, false, 3600 * 12);
	}
	//echo "total user count: $r";
	return $r;
	
}

function get_gender($user) {
	global $memcache, $DB, $facebook;
	$r = $memcache->get($user . "s_gender");
	
	
	if($r == FALSE) {
		$sql = "SELECT gender from users where id = ?"; 
		$r = $DB->GetOne($sql, array($user));
		
		if($r == 'm' || $r == 'f') {
			$memcache->set($user . "s_gender", $r, false, 1);

		}
		else {

			$re = $facebook->api_client->users_getInfo($user, array('sex'));

			//wtf why does this something throw exception!
			if($re[0]) {
			$gender = $re[0]['sex'];
			}
			else {
				$re0 = $re[0];
				error_log("error getting user info sex: re: $re re0 $re0 user: $user", 0);
				$gender = 'male';
			}
			
			
			$r = $gender;
			
			if($r == 'male') {
				$r = 'm';
			}
			else if($r == 'female') {
				$r = 'f';
			}
			else {
				$r = 'm';
			}
			
			$DB->Execute('update users set gender = ? where id = ?', array($r, $user));

		}
		
		
	}
	else {
		if ($r != 'm' && $r != 'f') {
			$re = $facebook->api_client->users_getInfo($user, array('sex'));
			$gender = $re[0]['sex'];
			$r = $gender;
			if($r == 'male') {
				$r = 'm';
			}
			else if($r == 'female') {
				$r = 'f';
			}
			else {
				$r = 'm';
			}
			
			$DB->Execute('update users set gender = ? where id = ?', array($r, $user));
		}
		
	
	}
	
	return $r;
	
}



function get_joke_on($user) {
	global $memcache, $DB;
	$r = $memcache->get($user . "s_joke_on_off");
	if($r == FALSE) {
		$sql = "SELECT joke_on from users where id = ?"; 
		$r = $DB->GetRow($sql, array($user));
		$memcache->set($user . "s_joke_on_off", $r, false, 1);
	}
	//echo "total user count: $r";
	return $r['joke_on'];	
}

function get_use_old_image($user) {
	global $memcache, $DB;
	$r = $memcache->get($user . "s_use_old_image");
	if($r == FALSE) {
		$sql = "SELECT use_old_image from users where id = ?"; 
		$r = $DB->GetOne($sql, array($user));
		$memcache->set($user . "s_use_old_image", $r, false, 1);
	}
	//echo "total user count: $r";
	return $r;
}



function get_bomb_on($user) {
	global $memcache, $DB;
	$r = $memcache->get($user . "s_bomb_on_off");
	if($r == FALSE) {
		$sql = "SELECT bomb_on from users where id = ?"; 
		$r = $DB->GetRow($sql, array($user));
		$memcache->set($user . "s_bomb_on_off", $r, false, 1);
	}
	//echo "total user count: $r";
	return $r['bomb_on'];	
}

function get_animate_on($user) {
	global $memcache, $DB;
	$r = $memcache->get($user . "s_an_on_off");
	if($r == FALSE) {
		$sql = "SELECT animate_on from users where id = ?"; 
		$r = $DB->GetRow($sql, array($user));
		$memcache->set($user . "s_an_on_off", $r, false, 1);
	}
	//echo "total user count: $r";
	return $r['animate_on'];	
}

function get_total_joke_count() {
	global $memcache, $DB;
	$r = $memcache->get("total_joke_count");
	if($r == FALSE) {
		$sql = "SELECT max(id) from jokes_approved"; 
		$r = $DB->GetOne($sql);
		$memcache->set("total_joke_count", $r, false, 60);
	}
	//echo "total user count: $r";
	return $r;
	
}

function get_first_name_for_id($user) {
	global $network_id;
	if($network_id == 0)  {
		return "<fb:name uid='$user' firstnameonly='true' shownetwork='false' useyou='false' linked='false'/>";
	}
	else {
		return get_name_for_id($user);
	}
}

function get_name_for_id($user) {
	global $DB;
	return $DB->GetOne('select name from users where id = ?', array($user));
}

function new_directed_battle($user, $touser) {
	global $memcache, $DB;
	$e = $touser;
	$memcache->set($e. 'attacked', 1, FALSE, rand(3000, 6000));
	
    $enemy = $DB->GetRow('select id, level, damage, team, coin_total from users where id = ?', array($e));
	//print_r($enemy);
	//store the battle
	
	$sql = 'insert into battles(user_id, user_id_2, battle_type, opponent_type, created_at) values (?, ?, ?, ?, now())';

	$enemy_id = $enemy['id'];
	$enemy_team = $enemy['team'];

	$DB->Execute($sql, array($user, $enemy_id, 's', $enemy_team));

	//print_r($enemy);
	return $enemy;
}

//return a battle id
function new_ship_battle_smart($user) {
	//  echo 'new ship';
  global $DB, $facebook, $memcache;
  $your_level = get_level($user);
  $your_team = get_team($user);
  //$level_low = floor($your_level * (1/3));
  //$level_high = ceil($your_level * (9/2));

  //if($level_low ==0) { //have to have 1 hit point at least...
  //  $level_low = 1;
  //}
  $total_users = get_total_user_count();
  
 // $random_enemies = array();
  //generate 500 random numbers
  //for($i = 0; $i < 10; $i++) {
  //	$random_enemies[]=rand(1, $total_users);
  //}
 // $random_enemy_string = implode(', ', $random_enemies);

  //echo "random enemy: $random_enemy";
  $msg = "Arrrr... The attack is on!";

  if($your_level > 100000) {
  	$active_users = $memcache->get('active_hi_3');
  }
  else if($your_level > 1000) {
  	$active_users = $memcache->get('active_hi_2');
  }  
  else if($your_level > 100) {
  	$active_users = $memcache->get('active_hi');
  }
  else {
  	$active_users = $memcache->get('active_lo');  
  }
  
  //print_r($active_users);
  $max_active_users = count($active_users);
  $ra = rand(0, $max_active_users - 1);
  
  //echo "  max active users is $max_active_users  ";
  //echo "   ra is $ra   ";
  
  $enemy = $active_users[$ra];
  //$enemy = random_enemy_id_for_battle_backup($user);

  $pvp_off = $memcache->get($enemy . 'pvp');
  if($pvp_off == 'off' || $enemy == $user) {
  		$enemy = random_enemy_id_for_battle_backup($user);
  }
  else {
  //print " enemy $enemy  ";
  
 	
  //set the enemy as being attacked and keep this for 15 minutes
  //it won't put them back in the pool
	
	
  unset($active_users[$ra]);

  $active_users = array_values($active_users);
	
  if(is_array($active_user)) {
  	$active_users = array();
  }
  if($your_level > 100000) {
        $active_users = $memcache->set('active_hi_3', $active_users);
  }
  else if($your_level > 1000) {
    $active_users = $memcache->set('active_hi_2', $active_users);
  }
  else  if($your_level > 100) {
  	$active_users = $memcache->set('active_hi', $active_users);
  	//echo 'unsetting high';
  	//print_r($active_users);	
  }
  else {
	$active_users = $memcache->set('active_lo', $active_users);
  }
  
  }
  //    $was_attacked = $memcache->get($enemy . 'attacked');

	$enemy_action = get_current_action($user);
	
	
	if($enemy == FALSE || $enemy == $user || $enemy_action == 'treasure_hunt') {
		$enemy = random_enemy_id_for_battle_backup($user);
	}
  	if($enemy == FALSE) {
  		return FALSE;
  	}
  	//echo "new enemy";
	$e = $enemy;
	$memcache->set($e. 'attacked', 1, FALSE, rand(3000, 6000));

	
	
   $enemy = $DB->GetRow('select id, level, damage, team, coin_total from users where id = ?', array($e));

	$enemy_id = $enemy['id'];

	$sql = 'update users set battling_enemy_id = ? where id = ?';
	$DB->Execute($sql, array($enemy_id, $user));
	
	$sql = 'insert into battles(user_id, user_id_2, battle_type, opponent_type, created_at) values (?, ?, ?, ?, now())';
	//print_r($enemy);
	
	
	$body = "Avast! <a href='http://facebook.com/profile.php?id=$user'><fb:name uid='$user'/></a> attacked <a href='http://facebook.com/profile.php?id=$enemy_id'><fb:name uid = '$enemy_id'/></a>!";

global $facebook_canvas_url;
global $base_url;
$image_1 = "$base_url/images/flag_200.jpg";
$image_1_link = "$facebook_canvas_url/?i=$user";
$title = "attacked a Pirate. Arrr...";


/*
//TODO add templatitzed feeds
try {
	$re2 = $facebook->api_client->feed_publishActionOfUser($title, $body);
}
   catch (FacebookRestClientException $fb_e) {
}
*/

//notify user on attack
//$facebook->api_client->notifications_send(array($enemy_id) , "  attacked you! Attack <fb:pronoun uid='$user' useyou='false' objective='true' /> back!");


	$DB->Execute($sql, array($user, $enemy_id, 's', $enemy_team));
	
	//$battle_id =  $DB->GetOne("select last_insert_id()");

	//echo "battle id: $battle_id";
	//var_dump($enemy);
	return $enemy;
}


function should_show_ad($user) {
  global $memcache;
  if($memcache) {
    $s = $memcache->get($user . ":z");
    //echo "s is $s";
    
    if($s == "") {
      //echo "returning true";
    	return 1;  // not set, default to true
    }
    
    return $s;
  }
  else {
  	return 1;
  }
}


function random_enemy_id_for_battle_backup($user) {
//echo "backup";
//return a random enemy id
  global $DB, $facebook;
  $your_level = get_level($user);
  $your_team = get_team($user);
  //$level_low = floor($your_level * (1/3));
  //$level_high = ceil($your_level * (9/2));

  //if($level_low ==0) { //have to have 1 hit point at least...
  //  $level_low = 1;
  //}
  $total_users = get_total_user_count();
  
  $random_enemies = array();
  //generate 20 random numbers
  for($i = 0; $i < 200; $i++) {
  	$random_enemies[]=rand(1, $total_users);
  }
  $random_enemy_string = implode(', ', $random_enemies);

  if($your_level < 200) {
    $sql = "select id, level, damage, team, coin_total from users where auto_id in($random_enemy_string) and id not in(select id from banned) and level !=0 and level != 1 and level != 2 and level != 3 and damage < level and id != ? and user_in_battle = 0 and level < 200 and pvp_off = 0 and current_action != 'treasure_hunt'";
  }
  else {
    $sql = "select id, level, damage, team, coin_total from users where auto_id in($random_enemy_string) and id not in(select id from banned) and level !=0 and level != 1 and level != 2 and level != 3 and damage < level and id != ? and user_in_battle = 0 and level > 200 and pvp_off = 0 and current_action != 'treasure_hunt'";
  }
  
 // print "sql $sql";
  $enemy = $DB->GetRow($sql, array($user));


  return $enemy['id'];
}


//return a battle id
function new_ship_battle($user) {
  global $DB, $facebook;
  $your_level = get_level($user);
  $your_team = get_team($user);
  //$level_low = floor($your_level * (1/3));
  //$level_high = ceil($your_level * (9/2));

  //if($level_low ==0) { //have to have 1 hit point at least...
  //  $level_low = 1;
  //}
  $total_users = get_total_user_count();
  
  $random_enemies = array();
  //generate 20 random numbers
  if($your_level > 800) {
    for($i = 0; $i < 1500; $i++) {
     $random_enemies[]=rand(1, $total_users);
    }
  }
  else {
    for($i = 0; $i < 200; $i++) {
     $random_enemies[]=rand(1, $total_users);
    }
  }
  $random_enemy_string = implode(', ', $random_enemies);

  //echo "random enemy: $random_enemy";
  $msg = "Arrrr... The attack is on!";



  
  //echo "level low: $level_low";
  //echo "level high: $level_high";
  //588360301
  //  $sql = 'select id, level, damage, team, coin_total from users where level between ? and ? and team != ? and coin_total > 0 and user_was_attacked = 0 and user_in_battle = 0 order by rand() limit 1';
  //pick up to 5 random users which are not flagged as already attacked and not level 0
  
if ($your_level > 800) {
  $sql = "select id, level, damage, team, coin_total from users where auto_id in($random_enemy_string) and id not in(select id from banned) and level !=0 and level != 1 and level != 2 and 
level != 3 and damage < level and level > 400 and pvp_off = 0 and id != ? and user_in_battle = 0 and current_action != 'treasure_hunt'";
  }
else if($your_level > 200) {
  $sql = "select id, level, damage, team, coin_total from users where auto_id in($random_enemy_string) and id not in(select id from banned) and level !=0 and 
level != 1 $level != 3 and damage < level and level > 150 and pvp_off = 0 and id != ? and user_in_battle = 0 and current_action != 'treasure_hunt'";
}
else if($your_level > 100) {
  $sql = "select id, level, damage, team, coin_total from users where auto_id in($random_enemy_string) and id not in(select id from banned) and level !=0 and level != 1 and level != 2 and
level != 3 and damage < level and level > 60 and pvp_off = 0 and id != ? and user_in_battle = 0 and current_action != 'treasure_hunt'";
}
else {
  $sql = "select id, level, damage, team, coin_total from users where auto_id in($random_enemy_string) and id not in(select id from banned) and level !=0 and level != 1 and level != 2 and 
level != 3 and level != 5 and level != 6 and level != 7 and level !=8 and damage < level and pvp_off = 0 and id != ? and user_in_battle = 0 and current_action != 'treasure_hunt'";
}

 //print "sql $sql";
  $enemy = $DB->GetRow($sql, array($user));
 //echo "your team : $your_team";
  //echo "the enemy";
    //print_r($enemy);
  
  	if($enemy == FALSE) {
  		return FALSE;
  	}

	$enemy_id = $enemy['id'];
	$sql = 'update users set battling_enemy_id = ? where id = ?';
	$DB->Execute($sql, array($enemy_id, $user));
	
	
	$sql = 'insert into battles(user_id, user_id_2, battle_type, opponent_type, created_at) values (?, ?, ?, ?, now())';
	
	
	
	$body = "Avast! <a href='http://facebook.com/profile.php?id=$user'><fb:name uid='$user'/></a> attacked <a href='http://facebook.com/profile.php?id=$enemy_id'><fb:name uid = '$enemy_id'/></a>!";

global $facebook_canvas_url;
global $base_url;
$image_1 = "$base_url/images/flag_200.jpg";
$image_1_link = "$facebook_canvas_url/?i=$user";
$title = "attacked a Pirate. Arrr...";


//for the user
/*
try {
	$re2 = $facebook->api_client->feed_publishActionOfUser($title, $body);
}
   catch (FacebookRestClientException $fb_e) {
} */


//notify user on attack
//$facebook->api_client->notifications_send(array($enemy_id) , "  attacked you! Attack <fb:pronoun uid='$user' useyou='false' objective='true' /> back!");


	$DB->Execute($sql, array($user, $enemy_id, 's', $enemy_team));
	
	//$battle_id =  $DB->GetOne("select last_insert_id()");

	//echo "battle id: $battle_id";
	//var_dump($enemy);
	return $enemy;
}




function hide_ad($user) {
  global $memcache;
  if($memcache) {
    $s = $memcache->set($user . ":z", 0);
    //return $s;
  }
}

function show_ad($user) {
  global $memcache;
  if($memcache) {
    $s = $memcache->set($user . ":z", 1);
    return $s;
  }
}

function get_max_miles($user) {
	$sail_level = get_sails($user);	
	
	$base_value = 75;

	$level = get_level($user);
	
	$sail_total_miles = $base_value + ($sail_level * 2);
	
//	$level = get_level($user);
	
	//$level = 75;
	//$level = 0;
//	$level = $level * 3;
//	if($level < 100) {
//		$level = 100;
//	}
//	if($level > 300) {
//		$level = 300;
//	}

	//hard limit of 1000 per hour
	if($sail_total_miles > 1000) {
		$sail_total_miles = 1000;
	}
	return $sail_total_miles;
}

function is_dynamite_set($user) {
	global $DB;
	
	return $DB->GetOne("select count(*) from booby_traps where to_id = ? and used = 0", array($user));
//1807687

//584114831

}

function get_recruits($user) {
	global $memcache, $DB;
	//$memcache = false;
	if(false) {
		$recruits_array = $memcache->get($user . ":fo");
		if(!is_bool($recruits_array) and $recruits_array != "") {
			return $recruits_array;
		}
	}
	
	$sql = "select * from users where recruited_by = ?"; 
		
	try {
		$recruits_array = $DB->GetArray($sql, array($user));
	} catch (Exception $e) { return false; }
				
	if($memcache) { 
		$memcache->set($user . ":fo", $recruits_array, false, 1800);
	}	
	
	return $recruits_array;
}

function get_sails($user) 
{
	$upgrades = get_upgrades($user);
	$sail_level = 0;
	foreach($upgrades as $key=>$value) {
		$upgrade_name = $value['upgrade_name'];
		$level = $value['level'];
		if($upgrade_name == 'sails') {
			return $level;
		}
		else {
			$sail_level = 0;
		}
	}
	return $sail_level;
}

function get_was_attacked($user) {
	global $memcache, $DB;
  if($memcache) {
    $user_was_attacked = $memcache->get($user . ":j");
    
    if($user_was_attacked == FALSE) {
    	$user_was_attacked = $DB->GetOne("select user_was_attacked from users where id = ?", array($user));
			$memcache->set($user . ":j", $user_was_attacked, FALSE, 600);
		}
  }
  else {
		$user_was_attacked = $DB->GetOne("select user_was_attacked from users where id = ?", array($user));		
  }

	//print "was attacked $user_was_attacked";
	
	return $user_was_attacked;
  
}



function get_was_bombed($user) {
	global $memcache, $DB;
  if($memcache) {
    $user_was_attacked = $memcache->get($user . ":b");
    
    if($user_was_attacked == FALSE) {
    	$user_was_attacked = $DB->GetOne("select user_was_bombed from users where id = ?", array($user));
			$memcache->set($user . ":b", $user_was_attacked);
		}
  }
  else {
		$user_was_attacked = $DB->GetOne("select user_was_bombed from users where id = ?", array($user));		
  }


	return $user_was_attacked;
  
}



function set_was_bombed($user, $enemy) {
	global $memcache, $DB;

	if($memcache) {
		$s = $memcache->set($user . ":b", $enemy);
	}
	$sql = 'update users set user_was_bombed = ? where id = ?';
	$DB->Execute($sql, array($enemy, $user));
	return $s;
}



function set_was_attacked($user, $enemy) {
	global $memcache, $DB;

	if($memcache) {
		$s = $memcache->set($user . ":j", $enemy);
	}
	$sql = 'update users set user_was_attacked = ? where id = ?';
	$DB->Execute($sql, array($enemy, $user));
	return $s;
}

function set_captcha_answer($user, $correct_answer) {
	global $memcache;

	if($memcache) {
		$s = $memcache->set($user . ":c", $correct_answer);
	}
	return $s;
}

function get_captcha_answer($user) {
	global $memcache;
	if($memcache) {
		$s = $memcache->get($user . ":c");
		
		if($s == "") {
			return ""; //not set
		}
		
		return $s;
	}
	else {
		return "";
	}
}
function get_defense_rating($user) {

//<span style='color:blue'>very easy</span>  <span style='color:green'>easy</span>  <span style='color:#C35817'>moderate</span>  <span style='color:red'>difficult</span>  <span style='color:maroon'>very difficult</span>  <span style='color:purple'>extremely difficult</span>
return "<span style='color:blue'>very easy</span>";
}
function get_miles_traveled($user) {
	global $memcache_temp;
	if($memcache_temp) {
		$s = $memcache_temp->get($user . ":m");
		
		if($s == "") {
			return 0; //not set, default to 0 miles sailed
		}
		
		return $s;
	}
	else { //I don't believe this should ever happen
		return 0;
	}
}

function increment_miles_traveled($user) {
	$s = get_miles_traveled($user);
	$s = $s + 1;
	
	$success = set_miles_traveled($user, $s);
	
	increment_miles_travelled_db($user);
	
	return $success;
}

function set_miles_traveled($user, $miles) {
	global $memcache_temp;

	if($miles < 0) {
		$miles = 0;
	}

	if($memcache_temp) {
		$s = $memcache_temp->set($user . ":m", $miles);
	}
	return $s;
}

//update weekly miles
function increment_miles_travelled_db($user) {
	global $memcache, $DB;
	
	$sql = "update users set weekly_miles = weekly_miles + 1 where id = ?";
	try {
		$DB->Execute($sql, array($user));
	} catch (Exception $e) { return false; }
	
	//clear memcache, when we need it later we'll refetch
	if($memcache) {
		$memcache->set($user . ":weekly_miles", "", false, 60);
	}
	
	return true;
}

function get_weekly_miles_traveled($user) {
	global $memcache, $DB;

	if($memcache) {
		$total_miles = $memcache->get($user . ":weekly_miles");
		if(!is_bool($total_miles) and $total_miles != "") {
			return "$total_miles";
		}
	}

	$sql = "select weekly_miles from users where id = ?";
	try {
		$total_miles = $DB->GetOne($sql, array($user));
	} catch (Exception $e) { return false; }
			
	if(is_bool($total_miles)) {
		return false;
	}
	
	if($memcache) {
		$memcache->set($user . ":weekly_miles", "$total_miles", false, 60);
	}
	
	return "$total_miles";
}

function get_weekly_money($user) {
	global $memcache, $DB;

	if($memcache) {
		$stored_money = $memcache->get($user . ":weekly_money");
		if(!is_bool($stored_money) and $stored_money != "") {
			return "$stored_money";
		}
	}

	$sql = "select weekly_money from users where id = ?";
	try {
		$stored_money = $DB->GetOne($sql, array($user));
	} catch (Exception $e) { return false; }
			
	if(is_bool($stored_money)) {
		return false;
	}
	
	if($memcache) {
		$memcache->set($user . ":weekly_money", "$stored_money", false, 60);
	}
	
	return "$stored_money";	
}

function get_weekly_level($user) {
	global $memcache, $DB;

	if($memcache) {
		$stored_level = $memcache->get($user . ":weekly_level");
		if(!is_bool($stored_level) and $stored_level != "") {
			return "$stored_level";
		}
	}

	$sql = "select weekly_level from users where id = ?";
	try {
		$stored_level = $DB->GetOne($sql, array($user));
	} catch (Exception $e) { return false; }
			
	if(is_bool($stored_level)) {
		return false;
	}
	
	if($memcache) {
		$memcache->set($user . ":weekly_level", "$stored_level", false, 60);
	}
	
	return "$stored_level";	
}

function get_last_weekly_winners($award_name) {
	global $memcache, $DB;

	if($memcache) {
		$stored_award = $memcache->get($user . ":$award_name");
		if(!is_bool($stored_award) and $stored_award != "") {
			return $stored_award;
		}
	}

	$sql = "select uid from leader_weekly_winners where award_won = ? and date_won >= now() - INTERVAL 1 WEEK order by date_won desc limit 1";
	try {
		$stored_award = $DB->GetArray($sql, array($award_name));
	} catch (Exception $e) { return false; }
	
	if(is_bool($stored_award)) {
		return false;
	}
	
	if($memcache) {
		$memcache->set($user . ":$award_name", $stored_award , false, 3600);
	}
	
	return $stored_award;		
}

function drink_rum($user) {
	$rumCount = get_rum_count($user);
	if($rumCount > 0) {
		set_rum_total($user, $rumCount - 1);
		$milesTraveled = get_miles_traveled($user);
		$milesTraveled = $milesTraveled - 50;
		if($milesTraveled < 0) {
			$milesTraveled = 0;
		}
		set_miles_traveled($user, $milesTraveled);
		return true;
	}
	return false;
}

function drink_rum_battling($user, $how_many) {
	$rumCount = get_rum_count($user);
	if($rumCount > 0) {
		set_rum_total($user, $rumCount - $how_many);
		return true;
	}
	return false;
}

function eat_ham($user) {
	$hamCount = get_ham_count($user);
	if($hamCount > 0) {
		set_ham_total($user, $hamCount - 1);
		$totalDamage = get_damage($user);
		$totalLevel = get_level($user);
		set_damage($user, ($totalDamage - round($totalLevel * .25))); 
		return true;
	}
	return false;
}

function in_us($user) {
	return false;
	global $facebook, $memcache;
	
	//$memcache->set($user . ":in_us", $in_us, 1);

  	if($memcache) {
    	$in_us = $memcache->get($user . ":in_us");
    	
    	if($in_us == FALSE) {
			$result = $facebook->api_client->users_getInfo($user, array('current_location'));
			$in_us= $result[0]['current_location']['country'];
		}
		
		if($in_us == "United States") {
			return true;
		}
		
		else {
			return false;
		}
	}


	$result = $facebook->api_client->users_getInfo($user, array('current_location'));
	$in_us= $result[0]['current_location']['country'];
	
	//echo $in_us;
	if($in_us == "United States") {
			return true;
		}
		
		else {
			return false;
		}



}

function get_encoded_interests($user) {
	global $facebook, $memcache;
	  
  	if($memcache) {
    	$google_hint = $memcache->get($user . ":google_hint");
    	
    	if($google_hint == FALSE) {
			$result = $facebook->api_client->users_getInfo($user, array('interests'));
			$google_hint= urlencode($result[0]['interests']);
			$memcache->set($user . ":google_hint", $google_hint);
		}
		
		return $google_hint;
	}


	$result = $facebook->api_client->users_getInfo($user, array('interests'));
	$google_hint= urlencode($result[0]['interests']);
	
	return $google_hint;


}
function cubics_468_60() {
	return "<center><fb:iframe src='http://cubics.com/displayAd.aspx?pid=18&plid=15965&adSize=468x60&bgColor=%23ffffff&textColor=%23000000&linkColor=%230033ff&channel=pirates' width='468' height='60' frameborder='0' border='0' scrolling='no'></fb:iframe></center>";
}


function adsense_336_280($user) {
	global $base_url;
	return "<center><fb:iframe id='adsense_336' name='adsense_336' src='$base_url/money/adsense_336.php' framespacing='0' frameborder='no' scrolling='no' width='336' height='290'></fb:iframe></center>";

}




function adsense_300_250($user) {
	global $network_id;
	global $base_url;
	
	if($network_id == 0) {
		return "<center><fb:iframe id='ad_300' name='ad_300' src='$base_url/money/ad_300_250.php' framespacing='0' frameborder='no' scrolling='no' width='300' height='250'></fb:iframe></center>";
	}
	else {
		return "<center><iframe id='ad_300' name='ad_300' src='$base_url/money/ad_300_250.php' framespacing='0' frameborder='no' scrolling='no' width='300' height='250'></iframe></center>";

	}
	
}


function adsense_200_200($user) {
	global $base_url, $network_id;
	if($network_id == 0) {
		return "<center><fb:iframe id='adsense_200' name='adsense_200' 
src='CHANGEME/ads/adsense_pirates_200_200.html' 
framespacing='0' frameborder='no' 
scrolling='no' width='210' height='210'></fb:iframe></center>";
	}
	else {
		return "<center><iframe id='adsense_200' name='adsense_200' 
src='CHANGEME/ads/adsense_pirates_200_200.html' 
framespacing='0' frameborder='no' 
scrolling='no' width='210' height='210'></iframe></center>";
	}
	
}

function adsense_125_125($user) {
	global $base_url;
	return "<center><fb:iframe id='adsense_125' name='adsense_125' 
src='$base_url/ads/adsense_pirates_125_125.html' 
framespacing='0' frameborder='no' scrolling='no' width='130' height='130'></fb:iframe></center>";

}




function adbrite_468_60 () {
	global $base_url;
	print "<center><fb:iframe src=\"$base_url/money/adbrite_468_60.php\" style=\"border:0px;\" width=\"468\" height=\"60\" scrolling=\"no\" frameborder=\"0\"/></center>";

}

function rmx_120($user) { 
		global $base_url;
		print "<center><fb:iframe src=\"$base_url/money/rmx_120.php\" style=\"border:0px;\" width=\"160\" height=\"600\" scrolling=\"no\" frameborder=\"0\"/></center>";


}

function rmx_300_250() {
	return '<fb:iframe FRAMEBORDER=0 MARGINWIDTH=0 MARGINHEIGHT=0 SCROLLING=NO WIDTH=300 HEIGHT=250 SRC="http://optimizedby.rmxads.com/st?ad_type=iframe&ad_size=300x250&section=246971"></fb:iframe>';
	

}

function outlaws_banner() {
	$str = <<<EOD
<center>
<div style="width: 90%;">
	<table>
		<tr>
			<td valign="center">
				<a href="http://apps.facebook.com/outlaws/"><fb:photo pid='367478' uid="777943571" /></a>
			</td><td valign="center">
				<h1><a href="http://apps.facebook.com/outlaws/"><font color="black">The makers of Pirates present:</font> <font color="saddlebrown">Outlaws!</font></a></h1>
				<h5><a href="http://apps.facebook.com/outlaws/">Ride, fight, and trade in the old west!</a></h5>
			</td><td>
				&nbsp;
			</td><td valign="center">
				<a href="http://apps.facebook.com/outlaws/"><fb:photo pid='367477' uid="777943571" /></a>
			</td>
		</tr>
	</table>
</div>
</center>
EOD;

return $str;

}
function cross_promo_banner($user) {
  global $base_url;
  //CHANGEME/ads/?site=pirates&user='. $user
  print '<fb:iframe src="' . $base_url . '/ads/show.php?site=pirates" width="646" height="65" scrolling="no" frameborder="0"/>';

}




function pubmatic_468_60 () {
	global $base_url;
	print "<center><fb:iframe src=\"$base_url/money/pubmatic_468_60.php\" style=\"border:0px;\" width=\"468\" height=\"60\" scrolling=\"no\" frameborder=\"0\"/></center>";

}

function valueclick_468_60() {
	$page = 'valueclick';
	global $base_url;
	return "<center><fb:iframe id='ad_468' name='ad_468' src='$base_url/money/$page.php' framespacing='0' frameborder='no' scrolling='no' width='468' height='75'></fb:iframe></center>";

}

function trianads_468_60() {
	$r = rand(1,10000);
	return "<a href=' http://www.trianads.com/adserver/www/delivery/ck.php?n=adfa8a74&amp;cb=$r' target='_blank'><img src='http://www.trianads.com/adserver/www/delivery/avw.php?zoneid=64&amp;cb=$r&amp;n=adfa8a74' border='0' alt='' /></a>";
	
}


function socialmedia_468_60() {
	return '<center><fb:iframe src="http://www.socialmedia.com/facebook/monetize.php?fmt=canvas&pubid=4fcd84edbc85d0d695c5cc8153c29678" border="0" width="645" height="60" scrolling="no"  frameborder="0"/>
</center>';

}
function adsense_120($user) {
	global $base_url, $network_id;
	if($network_id == 0) {
		return '<center><fb:iframe id="sometrics_ad" src="http://a.sometrics.com/a.html?zid=2707" height="600" width="120" frameborder="0" marginheight="0" marginwidth="0" scrolling="no"></fb:iframe></center>';
	}
	else {
		return '<center><iframe id="sometrics_ad" src="http://a.sometrics.com/a.html?zid=2707" height="600" width="120" frameborder="0" marginheight="0" marginwidth="0" scrolling="no"></iframe></center>';
	
	}

}

function adsense_468($user) {
	global $network_id;
if($network_id == 0) {	
	
	$rand = rand(1,2);
	if($rand == 1) {
	//print '<center><fb:iframe src="http://www.socialmedia.com/facebook/monetize.php?fmt=canvas&pubid=4fcd84edbc85d0d695c5cc8153c29678" border="0" width="645" height="60" scrolling="no"  frameborder="0"/>
	//</center>';
	print '<center><fb:iframe src="CHANGEME/ads/valueclick.html" width="728" height="90" border="0" frameborder="0" scrolling="no" /></center>';

	}
	/*
	else if ($rand == 2) {
	print '<center><fb:iframe src="CHANGEME/ads/adsense_pirates_468_60.html" width="468" height="70" border="0" frameborder="0" scrolling="no" /></center>';

	}*/
	
	else {
	//	return "<center><fb:iframe src='http://cubics.com/displayAd.aspx?pid=18&plid=15965&adSize=468x60&bgColor=%23ffffff&textColor=%23000000&linkColor=%230033ff&channel=pirates' width='468' height='60' frameborder='0' border='0' scrolling='no'></fb:iframe></center>";
	return "<center><fb:iframe src='http://social.bidsystem.com/displayAd.aspx?pid=18&plid=15965&adSize=728x90&bgColor=%23ffffff&textColor=%23000000&linkColor=%230033ff&channel=pirates-fb-728&appid=46&pfid=' width='728' height='90' frameborder='0' border='0' scrolling='no'></fb:iframe></center>";
	
	}

}
else {
                return "<center><iframe src='http://cubics.com/displayAd.aspx?pid=18&plid=15965&adSize=468x60&bgColor=%23ffffff&textColor=%23000000&linkColor=%230033ff&channel=CHANGEME' width='468' height='60 ' frameborder='0' border='0' scrolling='no'></iframe></center>";
}



}


function blow_up_user($user) {
//unsets dynamite set for this user and takes away 10% of their highest item
//unset dynamite
global $DB, $facebook;

$sql = 'select from_id from booby_traps where to_id = ? and used = 0 ORDER BY created_at';
$from_id = $DB->GetOne($sql, array($user));

//print "from id $from_id";

//figure out id of what you lose
$sql = 'SELECT how_many c, stuff_id FROM `stuff` where user_id = ? order by c desc limit 1'; 
$lost = $DB->GetRow($sql, array($user));
//print_r($lost);
$stuff_id = $lost['stuff_id'];
$how_many = $lost['c'];

$percent = rand(1,5);

$how_many_to_take = round($how_many * .01 * $percent); //take 1-5% of top item

$how_many_left = $how_many - $how_many_to_take;


//take away from user
$sql = 'update stuff set how_many = ? where stuff_id = ? and user_id = ?';
$DB->Execute($sql, array($how_many_left, $stuff_id, $user));


if($how_many_to_take > 10) {
	$how_many_to_take_limited = 10;
}
else {
	$how_many_to_take_limited = $how_many_to_take;
}
if($how_many_to_take_limited < 1) {
	$how_many_to_take_limited = 1;
}


//give to the pirate who set the trap
$sql = 'update stuff set how_many = how_many + ? where stuff_id = ? and user_id = ?';
$DB->Execute($sql, array($how_many_to_take_limited, $stuff_id, $from_id));



$stuff_stuff = get_booty_data_from_id($stuff_id);
//print_r($stuff_stuff);
$stuff_name = $stuff_stuff[0];
$sql = 'update booby_traps set used = 1 where to_id = ? and from_id = ? and used = 0 ORDER BY created_at LIMIT 1;';
$DB->Execute($sql, array($user, $from_id));

///notify_dynamite_went_off($from_id);

//send.notification for from_id telling them they blew up $user making them lose some booty


$ra = rand(1,3);
if($ra == 1) {
	$set_it_off  =  'crew members'; //todo check if they have each of these
}
else if($ra == 2) {
	$set_it_off = 'parrots';
}
else {
	$set_it_off = 'monkeys';
}

$stuff_name_plural = $stuff_name . 's';

if($stuff_name_plural == 'Gold Barss') {
$stuff_name_plural = 'Gold Barss';
}

if($stuff_name_plural == 'Rums') {
$stuff_name_plural = 'Bottles of Rum';
}


global $facebook_canvas_url;
if($how_many_to_take == 1) {
$it_them = 'it';
$the_stuff_name = $stuff_name;
	if($the_stuff_name == 'Gold Bars') {
		$the_stuff_name = 'Gold Bar';
	}

}
else {
$it_them = 'them';
$the_stuff_name = $stuff_name_plural;
}


$facebook->api_client->notifications_send(array($from_id) , " blew up while <a href='$facebook_canvas_url'>searching for booty</a>! Your dynamite trap worked - your monkey carried $how_many_to_take_limited $the_stuff_name back to <a href='$facebook_canvas_url/booty.php'>your ship</a>!", 'user_to_user');


global $you_lose_image, $image_uid;
return "<center><h1 style='font-size:200%; color: red'>KABOOOOOM!!!</h1><h1 style='padding:10px'>One of yer $set_it_off set off a dynamite booby trap while searching for booty!</h1><fb:photo pid='$you_lose_image' uid='$image_uid'/><h1 style='padding:10px'>Arrrrrrr... no $set_it_off be injured in the explosion but you be <b>losin $how_many_to_take $the_stuff_name</b>!</h1><h1 style='padding-top:10px'>Cap'n <fb:userlink ifcantsee='(Anonymous)' uid='$from_id' /> left this trap for you. Shiver me timbers!!</h1></center>";


}

function use_sextant($user) {
	$sextantCount = get_sextant_count($user);
	if($sextantCount > 0) {
		set_sextant_total($user, $sextantCount - 1);
		return true;
	}
	return false;
}

function get_user_list() {
	global $DB;
	$sql = "SELECT id FROM users";

	$value = $DB->GetArray($sql);
	return $value;
}	

function ordinal_suffix($value, $sup = 0){

    if(substr($value, -2, 2) == 11 || substr($value, -2, 2) == 12 || substr($value, -2, 2) == 13){
        $suffix = "th";
    }
    else if (substr($value, -1, 1) == 1){
        $suffix = "st";
    }
    else if (substr($value, -1, 1) == 2){
        $suffix = "nd";
    }
    else if (substr($value, -1, 1) == 3){
        $suffix = "rd";
    }
    else {
        $suffix = "th";
    }
    if($sup){
        $suffix = "<sup>" . $suffix . "</sup>";
    }
    return $value . $suffix;
}

function redirect_to_index_if_not_or($user, $action_1, $action_2) {
    if($user == 1807687 ) {
       return;
    }
	global $DB, $facebook, $facebook_canvas_url;	
	$u = get_current_action($user);
	
	if($u != $action_1 && $u != $action_2) {
		$facebook->redirect("$facebook_canvas_url/index.php");
		//echo "u $u 1 $action_1 $action_2";
	}
}

function redirect_to_index_if_not($user, $action) {
    if($user == 1807687 ) {
        return;
    }
    global $DB, $facebook, $facebook_canvas_url;
	//$u = $DB->GetOne("select current_action from users where id = $user");
	
	$u = get_current_action($user);
	
	//echo $u;
	
	if($u != $action) {
		$facebook->redirect("$facebook_canvas_url/index.php");
	}
}

function redirect_to_index_if_not_secondary($user, $action) {
    //if($user == 1807687) {
    //    return;
    //}
    global $DB, $facebook, $facebook_canvas_url;
	//$u = $DB->GetOne("select current_action from users where id = $user");
	
	$u = get_secondary_action($user);
	
	//echo $u;
	
	if($u != $action) {
		$facebook->redirect("$facebook_canvas_url/index.php");
	}
}


function redirect_if_action($user) {
	
	global $DB, $facebook, $facebook_canvas_url, $network_id;
	
	$secondary = get_secondary_action($user);
	$u = get_current_action($user);
	
	if($secondary == 'attacked_by_monster') {
		$facebook->redirect("$facebook_canvas_url/attacked_by_monster.php");
	}
	if($secondary == 'monster_attack_result') {
		$facebook->redirect("$facebook_canvas_url/monster_attack_result.php");
	}	
	//echo $u;
	else if($u == 'land') {
		$facebook->redirect("$facebook_canvas_url/found_land.php");
	}
	else if($u == 'enemy_base') {
		$facebook->redirect("$facebook_canvas_url/enemy_base.php");
	}
	else if($u == 'enemy_ship') {
		$facebook->redirect("$facebook_canvas_url/enemy_ship.php");
	}	
	else if($u == 'attack_ship') {
		$facebook->redirect("$facebook_canvas_url/attack_ship.php");
	}	
	else if($u == 'attack_ship_merchant') {
		$facebook->redirect("$facebook_canvas_url/attack_ship_merchant.php");
	}		
	else if($u == 'island') {
		$facebook->redirect("$facebook_canvas_url/island.php");
	}
	else if($u == 'captcha') {
		$facebook->redirect("$facebook_canvas_url/captcha_page.php");
	}
	else if($u == 'attacked_by_monster') {
		$facebook->redirect("$facebook_canvas_url/attacked_by_monster.php");
	}	
	else if($u == 'treasture_hunt') {
		$facebook->redirect("$facebook_canvas_url/index.php?msg=treasure-start");
	}	
}

/*
function redirect_if_action_myspace($user) {
	
	global $DB, $facebook, $facebook_canvas_url, $network_id;

	//$u = $DB->GetOne("select current_action from users where id = $user");
	
	//$u = get_current_action($user);
	
	$secondary = get_secondary_action($user);
	$u = get_current_action($user);
	
	//echo "secondary $secondary";
	//echo "u $u";
	
	if($secondary == 'attacked_by_monster') {
		//$facebook->redirect("$facebook_canvas_url/attacked_by_monster.php");
		require_once 'attacked_by_monster.php';
		exit();
		
	}
	if($secondary == 'monster_attack_result') {
		//$facebook->redirect("$facebook_canvas_url/monster_attack_result.php");
		require_once 'monster_attack_result.php';
		exit();
	}	
	//echo $u;
	else if($u == 'land') {
		//$facebook->redirect("$facebook_canvas_url/found_land.php");
		require_once 'found_land.php';
		exit();
	}
	else if($u == 'enemy_base') {
		//$facebook->redirect("$facebook_canvas_url/enemy_base.php");
		require_once 'enemy_base.php';
		exit();
	}
	else if($u == 'enemy_ship') {
		//$facebook->redirect("$facebook_canvas_url/enemy_ship.php");
		require_once 'enemy_ship.php';
		exit();
	}	
	else if($u == 'attack_ship') {
		//$facebook->redirect("$facebook_canvas_url/attack_ship.php");
		require_once 'attack_ship.php';
		exit();
	}	
	else if($u == 'attack_ship_merchant') {
		//$facebook->redirect("$facebook_canvas_url/attack_ship_merchant.php");
		require_once 'attack_ship_merchant.php';
		exit();
	}		
	else if($u == 'island') {
		//$facebook->redirect("$facebook_canvas_url/island.php");
		require_once 'island.php';
		exit();
	}
	else if($u == 'captcha') {
		$facebook->redirect("$facebook_canvas_url/captcha_page.php");
		//require_once 'captcha_page.php';
		//exit();
	}
	else if($u == 'attacked_by_monster') {
		//$facebook->redirect("$facebook_canvas_url/attacked_by_monster.php");
		require_once 'attacked_by_monster.php';
		exit();
	}	
	else if($u == 'treasture_hunt') {
		$facebook->redirect("$facebook_canvas_url/index.php?msg=treasure-start");

	}	
}
*/

function get_coin_total_buried($user) {
	global $DB;
	$coin = $DB->GetOne("select buried_coin_total from users where id = $user");
	if($coin < 0) {
		$coin = 0;
		$DB->Execute('update users set buried_coin_total = 0 where id = ?', $user, array($user));
	}
	return $coin;
}

function set_coin_total_buried($user, $coins) {
	global $DB;

	$sql = "update users set buried_coin_total = $coins where id = ?";
	$DB->Execute($sql, array($user));
}

/*
function get_booty($user) {
	global $DB;
	$sql = 'select booty_name, count(*) c from booty where user_id = ? group by booty_name';
	return $DB->GetArray($sql, array($user));

}
*/

function get_booty($user) {
	global $DB;
	$sql = 'select stuff_id, how_many from stuff where user_id = ?';
	return $DB->GetArray($sql, array($user));
}

function get_booty_reverse_order($user) {
	global $DB;
	$sql = 'select stuff_id, how_many from stuff where user_id = ? order by stuff_id desc';
	return $DB->GetArray($sql, array($user));
}

function  get_coin_total($user) {
	global $DB;
	$coin = $DB->GetOne("select coin_total from users where id = $user");
	if($coin < 0) {
		$coin = 0;
		$DB->Execute('update users set coin_total = 0 where id = ?', array($user));
	}
	return $coin;
}

function random_enemy($type) {
	$ra= rand(1,100);
	if($type == 'buccaneer') {
		if($ra < 50) {
			return 'corsair';
		}
		
		else {
			return 'barbary';
		}	
	}

	else if($type == 'corsair') {
		if($ra < 50) {
			return 'buccaneer';
		}
		
		else {
			return 'barbary';
		}	
	}	
			
	else { // barbary
		if($ra < 50) {
			return 'corsair';
		}
		
		else {
			return 'buccaneer';
		}	
	}	
	
}
function get_dynamite_count($user) {
	global $DB;
	$dynamite_id = 4;
	$sql = "select how_many from stuff where user_id = ? and stuff_id = ?";
	return $DB->GetOne($sql, array($user, $dynamite_id));	
}

function get_monkey_count($user) {
	global $DB;
	$dynamite_id = 7;
	$sql = "select how_many from stuff where user_id = ? and stuff_id = ?";
	return $DB->GetOne($sql, array($user, $dynamite_id));	
}


function get_parrot_count($user) {
	global $DB;
	$dynamite_id = 11;
	$sql = "select how_many from stuff where user_id = ? and stuff_id = ?";
	return $DB->GetOne($sql, array($user, $dynamite_id));	
}


function get_map_count($user) {
	global $DB;
	$map_id = 1;
	$sql = "select how_many from stuff where user_id = ? and stuff_id = ?";
	return $DB->GetOne($sql, array($user, $map_id));	
}

function get_bomb_count($user) {
	global $DB;
	$bomb_id = 6;
	$sql = "select how_many from stuff where user_id = ? and stuff_id = ?";
	return $DB->GetOne($sql, array($user, $bomb_id));	
}

function get_bottle_count($user) {
	global $DB;
	$bottle_id = 3;
	$sql = "select how_many from stuff where user_id = ? and stuff_id = ?";
	return $DB->GetOne($sql, array($user, $bottle_id));	
}

function set_bomb_total($user, $new_total) {
	global $DB;
	$bomb_id = 6;
	if($new_total < 0) {
		$new_total = 0;
	}
	$sql = 'update stuff set how_many=? where user_id = ? and stuff_id = ?';
	$DB->Execute($sql, array($new_total, $user, $bomb_id));
}

function get_rum_count($user) {
	global $DB;
	$bomb_id = 9;
	$sql = "select how_many from stuff where user_id = ? and stuff_id = ?";
	return $DB->GetOne($sql, array($user, $bomb_id));
}

function set_rum_total($user, $new_total) {
	global $DB;
	$bomb_id = 9;
	if($new_total < 0) {
		$new_total = 0;
	}
	$sql = 'update stuff set how_many=? where user_id = ? and stuff_id = ?';
	$DB->Execute($sql, array($new_total, $user, $bomb_id));
}

function get_round($user) {
  global $memcache;
  $r = $memcache->get($user . 'a_r');
  if($r == 0 || $r == FALSE) {
    $r = 1;
  }
  return $r;
}

function increment_round($user) {
  //if the round here is 0 or false, then just end it by setting to 9
  //it will get reset for the next fight
  $round = get_round($user);
  global $memcache;
  $round++;
  $memcache->set($user . 'a_r', $round);
}

function reset_round($user) {
  //resets round to 1
  global $memcache;
  $memcache->set($user . 'a_r', 1);
  
}



function get_ham_count($user) {
	global $DB;
	$bomb_id = 12;
	$sql = "select how_many from stuff where user_id = ? and stuff_id = ?";
	return $DB->GetOne($sql, array($user, $bomb_id));
}

function get_sextant_count($user) {
	global $DB;
	$bomb_id = 13;
	$sql = "select how_many from stuff where user_id = ? and stuff_id = ?";
	return $DB->GetOne($sql, array($user, $bomb_id));
}

function set_ham_total($user, $new_total) {
	global $DB;
	$ham_id = 12;
	if($new_total < 0) {
		$new_total = 0;
	}
	$sql = 'update stuff set how_many=? where user_id = ? and stuff_id = ?';
	$DB->Execute($sql, array($new_total, $user, $ham_id));
}

function set_dynamite_total($user, $new_total) {
	global $DB;
	$dynamite_id = 4;
	if($new_total < 0) {
		$new_total = 0;
	}
	$sql = 'update stuff set how_many=? where user_id = ? and stuff_id = ?';
	$DB->Execute($sql, array($new_total, $user, $dynamite_id));
}

function set_monkey_total($user, $new_total) {
	global $DB;
	$monkey_id = 7;
	if($new_total < 0) {
		$new_total = 0;
	}
	$sql = 'update stuff set how_many=? where user_id = ? and stuff_id = ?';
	$DB->Execute($sql, array($new_total, $user, $monkey_id));
}

function set_map_total($user, $new_total) {
	global $DB;
	$map_id = 1;
	if($new_total < 0) {
		$new_total = 0;
	}
	$sql = 'update stuff set how_many=? where user_id = ? and stuff_id = ?';
	$DB->Execute($sql, array($new_total, $user, $map_id));
}

function set_parrot_total($user, $new_total) {
	global $DB;
	$parrot_id = 11;
	if($new_total < 0) {
		$new_total = 0;
	}
	$sql = 'update stuff set how_many=? where user_id = ? and stuff_id = ?';
	$DB->Execute($sql, array($new_total, $user, $parrot_id));
}




function set_sextant_total($user, $new_total) {
	global $DB;
	$sextant_id = 13;
	if($new_total < 0) {
		$new_total = 0;
	}
	$sql = 'update stuff set how_many=? where user_id = ? and stuff_id = ?';
	$DB->Execute($sql, array($new_total, $user, $sextant_id));
}

function increment_booty($user, $stuff_id) {
	global $DB;
	
	$sql = 'insert into stuff (user_id, stuff_id, how_many, updated_at) values(?, ?, 1, now()) on duplicate key update how_many = how_many + 1';
	$DB->Execute($sql, array($user, $stuff_id));
}

function decrement_booty($user, $stuff_id) {
	global $DB;
	
	$sql = 'insert into stuff (user_id, stuff_id, how_many, updated_at) values(?, ?, 0, now()) on duplicate key update how_many = how_many - 1';
	$DB->Execute($sql, array($user, $stuff_id));
}

function explore($user) {
	//echo 'in function explore';
	global $DB, $memcache;
	
    //$user_attacked = $memcache->get($user . 'pvpattack');
	
	//10% chance to see land in the distance
	//10% chance to see another ship
	//80% chance of nothing happening.. just keep sailing

	$userMiles = get_miles_traveled($user);
	$milesMax = get_max_miles($user);
	if($userMiles < $milesMax) { 
   		$success = increment_miles_traveled($user);
	}
	
	$ra= rand(1,2000);
	//echo "ra; $ra";
	if($ra < 3) {
		update_action($user, "bottle");
		$bottle_count = get_bottle_count($user);
		
		//echo "bomb count: $bomb_count";
		if($bottle_count > 10) {
			update_action($user, "land");
			return 'land';
		}
        
        //add 1 bottle to the users inventory
        $bottle_id = 3;
        global $DB;
        $DB->Execute('insert into stuff (user_id, stuff_id, how_many, updated_at) values(?, ?, 1, now()) on duplicate key update how_many = how_many + 1', array($user, $bottle_id));
        
		return 'bottle';

	}
	else if($ra < 6) {
		if (get_level($user) > 500 && $ra != 4 && $ra != 3) {
	      	update_action($user, "land");
			return 'land';	   		
		}
		
		//check if they've already had a sinking ship today and if so forward to found land
		
		$sinking_ship_limit =  $memcache->get($user . 'sinking_ship_limit');
		//$memcache = false;
        if($memcache == false) {
      		update_action($user, "land");
			return 'land';	   
		}
		//$sinking_ship_limit == 1
        else if($sinking_ship_limit == 1) {
 			update_action($user, "land");
			return 'land';	      
        }
        else {
		//else set flag so they don't get it again
			$memcache->set($user . 'sinking_ship_limit', 1, false, 60 * 60 * 24);
		}
		
		//add 1-3 sails, cannons, or crew
		$upgrade_index = rand(0,2);
		$upgrades = array('crew', 'sails', 'cannons');
		$upgrade_name = $upgrades[$upgrade_index];
		
		//print_r($upgrade_index);
		//print_r($upgrade_name);
		$amount = rand(1,3);
		
		$cannon_limit = round(get_level($user)/2);
		if($cannon_limit > 200) {
			$cannon_limit = 200;
		}
		//echo "cannon limit $cannon_limit";
		if($upgrade_name == 'cannons') {
			$current_upgrade_level = get_cannons($user);
			if($current_upgrade_level > $cannon_limit ) {
				update_action($user, "land");
				return 'land';
			}
		}
		else if($upgrade_name == 'crew') {
			$current_upgrade_level = get_crew_count($user);
			if($current_upgrade_level > 150) {
				update_action($user, "land");
				return 'land';
			}
		}
		else if($upgrade_name == 'sails') {
			$current_upgrade_level = get_sails($user);
			if($current_upgrade_level > 300) {
				update_action($user, "land");
				return 'land';
			}
		
		}
		$sql = "insert into sinking_ship_booty (user_id, upgrade_name, amount, created_at) values(?, ?, ?, now())";
		$result = $DB->Execute($sql, array($user, $upgrade_name, $amount));
		
		
		$sql = "insert into upgrades (user_id, upgrade_name, created_at) values(?, ?, now()) on duplicate key update level = level+?";
		$result = $DB->Execute($sql, array($user, $upgrade_name, $amount));
		

		global $facebook;
		//forward to index with msg
		$facebook->redirect("index.php?msg=sinking-ship&booty=$upgrade_name&amount=$amount");
		
	
	}
	else if($ra < 20) {
		update_action($user, "parrot");
		$parrot_count = get_parrot_count($user);
		
		//echo "bomb count: $bomb_count";
		if($parrot_count > 0) {
			update_action($user, "land");
			return 'land';
		}
        //add 1 parrot to the users inventory
        $bottle_id = 11;
        global $DB;
        //$DB->GetOne('select count(*) from stuff 
        $DB->Execute('insert into stuff (user_id, stuff_id, how_many, updated_at) values(?, ?, 1, now()) on duplicate key update how_many = how_many + 1', array($user, $bottle_id));
        
		return 'parrot';

			
	}
	else if($ra < 100) {  
		if($network_id == 1) {
			update_action($user, "land");
			return 'land';
		}
		//10
		update_action($user, "bomb");
		$bomb_count = get_bomb_count($user);
		
		//echo "bomb count: $bomb_count";
		if($bomb_count < 1) {
			update_action($user, "land");
			return 'land';
		}
		
		$bomb_on = get_bomb_on($user);
		if($bomb_on == 0) {
			update_action($user, "land");
			return 'land';
		}
		
		return 'bomb';
	
	}
	else if($ra < 185) { //set to 0 to turn off ship fighting
		update_action($user, "ship");
		//echo "get health approx";
		//echo get_health($user);		
		global $DB;
		//$memcache;
		//$pvp_toggle = $memcache->get($user . 'pvp');
		$pvp_toggle = $DB->GetOne('select pvp_off from users where id = ?', array($user));
		if($pvp_toggle == 1) {
			update_action($user, "land");
			return 'land';		  
		}
		$merchant_limit =  $memcache->get($user . 'merchant_ship_limit');
        if($merchant_limit == 1) {
 			update_action($user, "land");
			return 'land';	      
        }
		
		if(get_health($user)< 1) {
			update_action($user, "land");
			return 'land';
		}
		//
		return 'ship';
	}
	else if($ra < 193) { //200
			update_action($user, 'found_merchant_ship');
			return 'found_merchant_ship';
	}
	else if($ra < 750) {  //70
		update_action($user, "land");
		return 'land';
	}
	else {
		return 'nothing';
	}
		
}

function get_health($user) {
	$level = get_level($user);
	$health = $level - get_damage($user);
	if($health > $level) {
		$health = $level;
		set_damage($user, 0);
	}
	return $health;
}

function get_gambling_count($user) {
	
	global $memcache_temp;
    $gambling_count = $memcache_temp->get($user . ":gamblecount");
	if($gambling_count == FALSE) {
		$gambling_count = 0;
	}
	
	return $gambling_count;
	
}

function get_date_joined($user) {
	$sql_formatted_datestring = get_user_created_at($user);
	
	return format_mysql_date($sql_formatted_datestring);
}

function format_mysql_date($sql_formatted_datestring) {

	$position = strpos($sql_formatted_datestring, " ");
	$date_string = substr($sql_formatted_datestring, 0, $position);
	
	//format of the string now is 2007-10-19 as an example, need to rearrange
	$date_array = explode("-", $date_string);
	$formatted_date_string = $date_array[1] . "/" . $date_array[2] . "/" . $date_array[0];
	
	return $formatted_date_string;
}

function get_user_created_at($user) {
	global $DB, $memcache;
	
	if($memcache) {
		$created_at = $memcache->get($user . ":crtd");
		if($created_at != "" and !is_bool($created_at)) {
			return $created_at;
		}
	}
	
	$sql = "select created_at from users where id=$user";
	$created_at = $DB->GetOne($sql);
	
	if($memcache) {
		$memcache->set($user . ":crtd", $created_at, false, 3600);
	}
	
	return $created_at;
}

function secret_encode($string, $key)
{
return $string;
/*
require_once 'security.inc.php';
global $salt;

$key = $salt;
//print "salt is $salt";
$result = '';
for($i=1; $i<=strlen($string); $i++)
{
$char = substr($string, $i-1, 1);
$keychar = substr($key, ($i % strlen($key))-1, 1);
$char = chr(ord($char)+ord($keychar));
$result.=$char;
}
return $result;
*/
}

function secret_decode($string, $key)
{
return $string;
/*
require_once 'security.inc.php';
global $salt;

$key = $salt;

$result = '';
for($i=1; $i<=strlen($string); $i++)
{
$char = substr($string, $i-1, 1);
$keychar = substr($key, ($i % strlen($key))-1, 1);
$char = chr(ord($char)-ord($keychar));
$result.=$char;
}
return $result;
*/
}

function increment_gambling_count($user) {
	
	$gambling_count = get_gambling_count($user);
	global $memcache_temp;
	$memcache_temp->set($user . ":gamblecount", $gambling_count + 1);
	return true;
	
}

function update_team($user, $enemy) {
	global $DB, $memcache;
	if($memcache) {
    	$team = $memcache->set($user . ":t", $team);
	}
	
	$DB->Execute("update users set team='$enemy' where id = $user");
}

function update_coins($user, $coin) {
	global $DB;
	$DB->Execute("update users set coin_total=coin_total+$coin where id = $user");	
}



function log_coins($user, $amount, $action, $secondary_user = 0) {
	if (!in_array($user, get_audited_users_ids())) {
		return;
	}
	global $DB;
	if(is_null($secondary_user)) {
		$secondary_user = 0;
	}
	
	$coin_total = get_coin_total($user);
	$buried_coin_total = get_coin_total_buried($user);
	
	$action = $action . ' mile: ' . get_miles_traveled($user);
	$DB->Execute("insert into coin_log (user_id, amount, date, action, secondary_user, current_coin_total, current_buried_coin_total) values(?, ?, now(), ?, ?, ?, ?)", array($user, $amount, $action, $secondary_user, $coin_total, $buried_coin_total));

}

function log_levels($user, $action, $secondary_user = 0) {
	if (!in_array($user, get_audited_users_ids())) {
		return;
	}
	global $DB;
	if(is_null($secondary_user)) {
		$secondary_user = 0;
	}
	$action = $action . ' mile: ' . get_miles_traveled($user);
	$DB->Execute("insert into level_log (user_id, date, action, secondary_user) values(?, now(), ?, ?)", array($user, $action, $secondary_user));

}



function set_coins($user, $coin) {
	if($coin < 0) {
		$coin = 0;
	}
	global $DB;
	$DB->Execute("update users set coin_total=$coin where id = $user");	
}

function update_action($user, $action) {
	global $DB, $memcache;
	if($memcache) {
    	$team = $memcache->set($user . ":a", $action);
    	$DB->Execute("update users set current_action='$action' where id = $user");
  	}
  	else {
  		$DB->Execute("update users set current_action='$action' where id = $user");	    
  	}  
}

function update_secondary_action($user, $action) {
	global $DB, $memcache;
	if($memcache) {
    	$team = $memcache->set($user . ":se", $action);
    	$DB->Execute("update users set secondary_action='$action' where id = $user");
  	}
  	else {
  		$DB->Execute("update users set secondary_action='$action' where id = $user");	    
  	}  
}

function get_current_action($user) {
  global $memcache;
  
  if($memcache) {
    $team = $memcache->get($user . ":a");
    return $team;
  }
  else {
	  global $DB;
	  $sql = "select current_action from users where id=$user";
	  $r = $DB->GetOne($sql);
	  $team = $memcache->set($user . ":a", $r);
	  return $r;
  }
}


function get_secondary_action($user) {
  global $memcache;
  
  if($memcache) {
    $team = $memcache->get($user . ":se");
    return $team;
  }
  else {
	  global $DB;
	  $sql = "select secondary_action from users where id=$user";
	  $r = $DB->GetOne($sql);
	  $team = $memcache->set($user . ":se", $r);
	  return $r;
  }
}


function calculate_and_get_level($user) {
	global $DB;
	$sql = "select count(*) from users where path like '%$user/%'";
	//print $sql;
	$count=  $DB->GetOne($sql);
	$DB->Execute("update users set level=$count, level_is_correct=1 where id = $user");	
	return $count;
}

function get_level($user) {
	if($user == NULL) {
		return '';
	}
	global $memcache, $DB;
	
	if(false) { //todo turn memcache back on
	//if($memcache) {
    $level = $memcache->get($user . ":l");
    //echo "team from memcache is: $team";
    if($level == FALSE) {
			$sql = "select level from users where id = ?";
			$v = $DB->GetOne($sql, array($user));
			$memcache->set($user . ":l", $v, FALSE, 600);
			return $v;
		}
	return $level;
   }
 
 	global $DB;
	$sql = "select level from users where id = $user";
	return $DB->GetOne($sql);
}

function get_too_many_miles_msg($user) {
	//if(get_level($user) < 20) {
		//increase_level($user);
	// 	echo "<span style='font-size:200%; color:#FFFFFF;'>You leveled up!</span>";
	
	//}
	$milesMax = get_max_miles($user);
	global $base_url;
	$adsense_ads = '<br><br><div style="background-color:#FFFFFF; position: relative; left: -5px; padding:0px; margin:0px""<fb:iframe src="CHANGEME/ads/?site=pirates&user='. $user. '" style="border:0px;" width="626" height="80" scrolling="no" frameborder="0"/></div>';
	
	$out = '<span style="font-size:200%; color:#FFFFFF;">You\'ve sailed ';
	$out .= "$milesMax nautical miles</span><br><br><span style=\"font-size:125%; color:#FFFFFF;\">";
	$out .="Yarrr crew demands rest, rum, and wenches!</span><br><span style=\"color:#FFFFFF;\">(resets <strong>every hour</strong>)";
	
	$out .= "<br><br><br><h2>";
	$rumCount = get_rum_count($user);
	if($rumCount > 0) {
		$out .= "<a style=\"color:#FFFFFF;\" href=\"item_action.php?item=rum\">Drink Rum (x$rumCount)- sail an additional 50 miles</a><br><br>";
	}
	$out .= "<a style=\"color:#FFFFFF;\" href=\"tavern.php\">Go to the Tavern - buy wenches or rum to sail more</a><br>";
	$out .= "<a style=\"color:#FFFFFF;\" href=\"harbor.php\">Back to Harbor - upgrading your sails will help you sail farther</a>";
	
	$out .= "</h2>";
	$out .="</span>";
	
	//$out .= '<fb:iframe src="$base_url/money/neverblue.php" />';
	//print "$base_url/money/neverblue.php";
	//$url = "$base_url/money/neverblue.php";
	
	//$out .= $adsense_ads;
	
	//$out .= "<center><br><a style='font-size:250%; color:#FFFFFF' href='http://x.azjmp.com/0kf1Y?azauxurl=34563'>Happy Halloween! Get Yer Halloween Costume Avatar!</a></center><br>";
	
	//$out .= "<br><br><center><fb:iframe frameborder='no' scrolling='no' height='260' width='310' marginheight='5' marginwidth='5' style='border-bottom: 1px solid #f7f7f7; overflow: hidden; background-color: #f7f7f7;' src='$url' /></center>";
	
	return $out;
}

function set_damage($user, $damage) {
	global $DB;
	
	$level = get_level($user);
	//safety checks to prevent errors
	if($damage > $level) {
		$damage = $level;
	}
	else if($damage < 0) {
		$damage = 0;
	}
	
	$sql = 'update users set damage = ? where id = ?';
	$DB->Execute($sql, array($damage, $user));
	
	return $s;
}

function set_level($user, $l) {
	
	global $memcache, $DB;
	if($memcache) {
		$s = $memcache->set($user . ":l", $l);
	}
	
	
	$sql = 'update users set level = ? where id = ?';
	$DB->Execute($sql, array($l, $user));
	
	
	return $s;
}

//not that important, just store in memcache
function set_captcha_fail_count($user, $fail_count) {
	global $memcache;
	if($memcache) {
		$s = $memcache->set($user . ":f", $fail_count);
	}
}

//not that important, just store in memcache
function get_captcha_fail_count($user) {
	global $memcache;
	if($memcache) {
    	$fail_count = $memcache->get($user . ":f");
	}
	
	if($fail_count == FALSE) {
		$fail_count = 0;
	}
	
	return $fail_count;
}

function get_damage($user) {
	global $DB;
	
	$sql = "select damage from users where id = $user";
	return $DB->GetOne($sql);
}

function level_is_correct($user) {
	global $DB;
	$sql = "select level_is_correct from users where id=$user";
	return $DB->GetOne($sql);
}

function is_user_in_db($user) {
	global $DB;
	$sql = "select count(*) from users where id = $user";
	$count=  $DB->GetOne($sql);
	return $count;
}

function user_already_recruited($user) {
	global $DB;
	$c = $DB->GetOne("select count(*) from users where id = $user");	
	if($c !=0 ) {
		return TRUE;
	}
	else {
		return FALSE;
	}
}


function get_buccaneer_count() {
	return false;
	global $memcache, $DB;
	$r = $memcache->get("buccaneer_count");
	if($r == FALSE) {
		$r = $DB->GetOne("select count(*) from users where team = 'buccaneer'");
		
		$memcache->set("buccaneer_count", $r, FALSE, 36000 * 6);
	}
	
	return $r;
}

function get_corsair_count() {
	return false;
	global $memcache, $DB;
	$r = $memcache->get("corsair_count");
	if($r == FALSE) {
		$r = $DB->GetOne("select count(*) from users where team = 'corsair'");
		
		$memcache->set("corsair_count", $r, false, 36000 * 6);
	}
	
	return $r;
}

function get_barbary_count() {
	return false;
	global $memcache, $DB;
	$r = $memcache->get("barbary_count");
	if($r == FALSE) {
		$r = $DB->GetOne("select count(*) from users where team = 'barbary'");
		
		$memcache->set("barbary_count", $r, false, 36000 * 6);
	}
	
	return $r;
}

function kill_crewmember($user_id) {
	global $DB;
	$upgrade_name = "crew";
	$sql = "insert into upgrades (user_id, upgrade_name, created_at) values(?, ?, now()) on duplicate key update level = level-1";
	
	$result = $DB->Execute($sql, array($user_id, $upgrade_name));

}

function kill_crewmembers($user_id, $how_many) {
	global $DB;
	$upgrade_name = "crew";
	$sql = "insert into upgrades (user_id, upgrade_name, created_at) values(?, ?, now()) on duplicate key update level = level-?";
	
	$result = $DB->Execute($sql, array($user_id, $upgrade_name, $how_many));

}

function buy_upgrade($user_id, $upgrade_name) {
	global $DB;
	$DB->StartTrans();
	$sql = "insert into upgrades (user_id, upgrade_name, created_at) values(?, ?, now()) on duplicate key update level = level+1";
	

	$result = $DB->Execute($sql, array($user_id, $upgrade_name));
	$DB->FailTrans();

}

function buy_upgrade_transaction($user_id, $upgrade_name, $cost) {
	
	global $DBNoPersist;
	
	$DBNoPersist->StartTrans();

	$ok = $DBNoPersist->Execute("update users set coin_total=coin_total-$cost where id = $user_id");	

	$sql = "insert into upgrades (user_id, upgrade_name, created_at) values(?, ?, now()) on duplicate key update level = level+1";
	
	$ok = $DBNoPersist->Execute($sql, array($user_id, $upgrade_name));
	$DBNoPersist->CompleteTrans();

}

function get_upgrades($user) {
	global $DB;
	$sql = "select upgrade_name, level from upgrades where user_id = $user";
	//print $sql;
	return $DB->GetArray($sql);
}

function get_cannons($user) {
	global $DB;
	$sql = "select level from upgrades where user_id = $user and upgrade_name = 'cannons'";
	return $DB->GetOne($sql);
}

function get_hull($user) {
	global $DB;
	$sql = "select level from upgrades where user_id = $user and upgrade_name = 'hull'";
	return $DB->GetOne($sql);
}

function get_crew_count($user) {
    global $DB;
    return $DB->GetOne('select level from upgrades where user_id = ? and upgrade_name = "crew"', array($user));
}

function get_army($user) {
	global $DB;
	$sql = "select name, level, id from users where path like '%$user/%'";
	//print $sql;
	return $DB->GetArray($sql);
}

function get_days_ago_infected($user) {
	global $DB;
	$sql = "select created_at from users where id=$user";
	$created = $DB->GetOne($sql);
	$a = relative_date($nnnew_created);
	return $a;
}

function time_left_of_1week($posted_date) {
	global $memcache, $outlaws_memcache, $DB;

    $posted_date = str_replace("-", "", $posted_date);
	$posted_date = str_replace(":", "", $posted_date);
	$posted_date = str_replace(" ", "", $posted_date);
    
    $in_seconds = strtotime(substr($posted_date,0,8).' '.
                  substr($posted_date,8,2).':'.
                  substr($posted_date,10,2).':'.
                  substr($posted_date,12,2));

//	$now_string = $memcache->get($outlaws_memcache . ":time");
	
    //get the current time from the db
    if($now_string == false) {
    	$sql = "select now()";
		try {
			$now_string = $DB->GetOne($sql);
		} catch (Exception $e) {
			return "Unknown";
		}
//		$memcache->set($outlaws_memcache . ":time", $now_string, false, 30);  //expire after 30 seconds
	}
	
	$now_string = str_replace("-", "", $now_string);
	$now_string = str_replace(":", "", $now_string);
	$now_string = str_replace(" ", "", $now_string);  

    $now_time = strtotime(substr($now_string,0,8).' '.
                  substr($now_string,8,2).':'.
                  substr($now_string,10,2).':'.
                  substr($now_string,12,2));
    
    $now_time = $now_time + 31;
                  
    $diff = $now_time - $in_seconds;
    $months = floor($diff/2592000);
    $diff -= $months*2419200;
    $weeks = floor($diff/604800);
    $diff -= $weeks*604800;
    $days = floor($diff/86400);
    $diff -= $days*86400;
    $hours = floor($diff/3600);
    $diff -= $hours*3600;
    $minutes = floor($diff/60);
    $diff -= $minutes*60;
    $seconds = $diff;
    
    //24 hours == 1440 minutes
    $minutes_difference = (($days * 24) * 60) + $hours * 60 + $minutes;
    $time_left = (1440 * 7) - $minutes_difference;
    $days_left = floor(floor($time_left / 60) / 24);
    $hours_left = floor(($time_left - ($days_left * 24 * 60)) / 60);
    $minutes_left = $time_left - ($hours_left * 60) - ($days_left * 24 * 60);

    if($days_left ==  1) {
    	echo $days_left . " day ";
    } else if($days_left > 0) {
    	echo $days_left . " days ";
    }
    
    if($hours_left == 1) {
    	echo $hours_left . " hour ";
    } else if($hours_left > 0) {
    	echo $hours_left . " hours ";
    }
    
    if($minutes_left == 1) {
    	echo $minutes_left . " minute";
    } else if($minutes_left > 0) {
    	echo $minutes_left . " minutes";
    }
}

function time_left_of_24hours($posted_date) {
	global $memcache, $DB;

    $posted_date = str_replace("-", "", $posted_date);
	$posted_date = str_replace(":", "", $posted_date);
	$posted_date = str_replace(" ", "", $posted_date);
    
    $in_seconds = strtotime(substr($posted_date,0,8).' '.
                  substr($posted_date,8,2).':'.
                  substr($posted_date,10,2).':'.
                  substr($posted_date,12,2));

	$now_string = $memcache->get(":time");
	
    //get the current time from the db
    if($now_string == false) {
    	$sql = "select now()";
		try {
			$now_string = $DB->GetOne($sql);
		} catch (Exception $e) {
			return "Unknown";
		}
		$memcache->set(":time", $now_string, false, 30);  //expire after 30 seconds
	}
	
	$now_string = str_replace("-", "", $now_string);
	$now_string = str_replace(":", "", $now_string);
	$now_string = str_replace(" ", "", $now_string);  

    $now_time = strtotime(substr($now_string,0,8).' '.
                  substr($now_string,8,2).':'.
                  substr($now_string,10,2).':'.
                  substr($now_string,12,2));
    
    $now_time = $now_time + 31;
                  
    $diff = $now_time - $in_seconds;
    $months = floor($diff/2592000);
    $diff -= $months*2419200;
    $weeks = floor($diff/604800);
    $diff -= $weeks*604800;
    $days = floor($diff/86400);
    $diff -= $days*86400;
    $hours = floor($diff/3600);
    $diff -= $hours*3600;
    $minutes = floor($diff/60);
    $diff -= $minutes*60;
    $seconds = $diff;
    
    //24 hours == 1440 minutes
    $minutes_difference = $hours * 60 + $minutes;
    $time_left = 1440 - $minutes_difference;
    $hours_left = floor($time_left / 60);
    $minutes_left = $time_left - ($hours_left * 60);
    if($hours_left > 0) {
    	echo $hours_left . " hours ";
    }
    else if($hours_left == 1) {
    	echo $hours_left . " hour ";
    }
    
    if($minutes > 0) {
    	echo $minutes_left . " minutes";
    }
    else if($minutes_left == 1) {
    	echo $minutes_left . " minute";
    }
}

function relative_date($posted_date, $two) {
    /**
        This function returns either a relative date or a formatted date depending
        on the difference between the current datetime and the datetime passed.
            $posted_date should be in the following format: YYYYMMDDHHMMSS
        
        Relative dates look something like this:
            3 weeks, 4 days ago
        Formatted dates look like this:
            on 02/18/2004
        
        The function includes 'ago' or 'on' and assumes you'll properly add a word
        like 'Posted ' before the function output.
    **/
    
    $posted_date = str_replace("-", "", $posted_date);
	$posted_date = str_replace(":", "", $posted_date);
	$posted_date = str_replace(" ", "", $posted_date);
    
    $in_seconds = strtotime(substr($posted_date,0,8).' '.
                  substr($posted_date,8,2).':'.
                  substr($posted_date,10,2).':'.
                  substr($posted_date,12,2));
   $two_in_seconds = strtotime(substr($two,0,8).' '.
                  substr($two,8,2).':'.
                  substr($two,10,2).':'.
                  substr($two,12,2));
     //its one hour off for the racing stuff...can't use this function anywhere else for now            
    $diff = $two_in_seconds-$in_seconds;
    $months = floor($diff/2592000);
    $diff -= $months*2419200;
    $weeks = floor($diff/604800);
    $diff -= $weeks*604800;
    $days = floor($diff/86400);
    $diff -= $days*86400;
    $hours = floor($diff/3600);
    $diff -= $hours*3600;
    $minutes = floor($diff/60);
    $diff -= $minutes*60;
    $seconds = 60*60 - $diff; //changed!!

    if ($months>0) {
        // over a month old, just show date (mm/dd/yyyy format)
        return 'on '.substr($posted_date,4,2).'/'.substr($posted_date,6,2).'/'.substr($posted_date,0,4);
    } else {
        if ($weeks>0) {
            // weeks and days
            $relative_date .= ($relative_date?', ':'').$weeks.' week'.($weeks>1?'s':'');
            $relative_date .= $days>0?($relative_date?', ':'').$days.' day'.($days>1?'s':''):'';
        } elseif ($days>0) {
            // days and hours
            $relative_date .= ($relative_date?', ':'').$days.' day'.($days>1?'s':'');
            $relative_date .= $hours>0?($relative_date?', ':'').$hours.' hour'.($hours>1?'s':''):'';
        } elseif ($hours>0) {
            // hours and minutes
            $relative_date .= ($relative_date?', ':'').$hours.' hour'.($hours>1?'s':'');
            $relative_date .= $minutes>0?($relative_date?', ':'').$minutes.' minute'.($minutes>1?'s':''):'';
        } elseif ($minutes>0) {
            // minutes only
            $relative_date .= ($relative_date?', ':'').$minutes.' minute'.($minutes>1?'s':'');
        } else {
            // seconds only
            //$relative_date .= ($relative_date?', ':'').$seconds.' second'.($seconds>1?'s':'');
            $relative_date = round($seconds / 60) . ' minutes';
        }
    }
    // show relative date and add proper verbiage
    return $relative_date;
}


function get_infector($user) {
	global $DB;
	$sql = "select recruited_by from users where id=$user";
	return $DB->GetOne($sql);
}



function friend_count_not_infected($user) {
	global $facebook, $DB;
	$friend_ids = $facebook->api_client->friends_get();
	//print_r($friend_ids);
	$friend_string = implode(', ', $friend_ids);
	
	//get friendcount of this user in our db (regardless of type)
	$sql = "select count(*) from users where id in ($friend_string)";
	//print $sql;
	$friends_in_db_count = $DB->GetOne($sql);
	
	return sizeof($friend_ids) - $friends_in_db_count;
	
}





/*
get bucaneer, barbary, or corsair for a user.  if we dont have the user, random
*/
function get_team($user) {
	if($user == '') {
		return;
	}
	global $DB, $memcache;
	//this translation stuff sucks...
	//TODO switch the database to use the letters too..
	
	if($memcache) {
    $team = $memcache->get($user . ":t");
    //echo "team from memcache is: $team";
    if($team == 'u') {
      return 'buccaneer';
    }
    else if($team == 'c') {
      return 'corsair';
    }
    else if($team == 'a') {
      return 'barbary';
    }
  }

	
	$query = "SELECT team FROM users WHERE id=" . $user;
	$value = $DB->GetOne($query);
	
	if($memcache) {
	  if($value == 'buccaneer') {
	    $v_to_store = 'u';
	  }
	  else if($value == 'corsair') {
	    $v_to_store = 'c';
	  }
	  else if($value == 'barbary') {
	    $v_to_store = 'a';
	  }
	  
    $memcache->add($user . ":t", $v_to_store);
    //echo "setting team in memcache: $value";
  
  }
  
  //lazy fix to buccaneer mispelling
  if($value == 'bucaneer') {
  	$value = 'buccaneer';
  }
   
  return $value;
	
	/*
	if ($value != null)
		return $value;
	else
	{
		$ra= rand(1,100);
		
		if($ra < 33) {
			return 'bucaneer';
		}
		
		else if($ra < 33) {
			return 'barbary';
		}	
			
		else {
			return 'corsair';
		}
	}
	*/
	

}


function get_team_random($who) {
	global $DB;
	$query = "SELECT team FROM users WHERE id=" . $who;
	$value = $DB->GetOne($query);
	return $value;

}



// Returns an array of friends who you have the program installed

function get_installed_friends()
{
	global $facebook, $DB;
	$friends = $facebook->api_client->friends_get(); 
	$installedFriends = array();
	foreach ($friends as $value)
	{
		//SELECT id, name, type, level
		if (($friend = $DB->GetRow("SELECT id FROM users WHERE id = '". $value . "'")) != null)
		{
			$installedFriends[] = $friend[0];
		
		}
	
	}
	
	//print_r($installedFriends);
	return $installedFriends;
}

function get_profile_box($user) {


	global $network_id, $facebook, $facebook_canvas_url, $base_url;
	if($network_id == 1) {
		return;
	}
	
	$level = get_level($user);
	//$type = get_type_for($user);
	//$type_name = get_type_name_for($user);
	$team = ucwords(get_team($user));
	$coins = number_format(get_coin_total($user));
        $buried_coins = number_format(get_coin_total_buried($user));
	global $DB, $memcache;
	
	$joke_on = get_joke_on($user);
	
	if($joke_on == 1) {
		$ra = rand(1,get_total_joke_count());
		$r = $DB->GetRow('select * from jokes_approved where id = ?', array($ra));

	$question =$r['question'];
	$answer =$r['answer'];
	//$user =$r['user'];
	}
	
	
	$use_old_image = get_use_old_image($user);
	
	if($use_old_image == 0) {
	
		$gender = get_gender($user);
		if($gender == 'f') {
			$genderlong = 'female';
		}
		else {
			$genderlong = 'male';
		}

		if($level > 200) {
			$levelrank = 3;
		}
		else if($level > 50) {
			$levelrank = 2;
		}
	
		else {
			$levelrank =1;
		}
		$imagefile = "pirate_" . $genderlong . "_" . $levelrank .".jpg";
	}
	else {
		$imagefile = 'Piratey.jpg';
	}
	
	//$type_name_plural = get_type_name_plural_for($user);
	$fbml = "<center><h1 style=\"text-align:center\">Shiver me timbers!</h1>";
	if($use_old_image == 0) {
		$fbml .= "<a href='$facebook_canvas_url/?i=$user'><img height='300px' src='$base_url/images/a/$imagefile'></a><br>";
	}
	else {
		$fbml .= "<a href='$facebook_canvas_url/?i=$user'><img width='200px' src='$base_url/images/a/$imagefile'></a><br>";
	
	}
	
	$fbml .= "It's Cap'n <fb:name useyou='false' uid='$user'/></h1>";
	$fbml .="<h2 style='text-align:center'>A Level $level $team Pirate</h2>";
	$fbml .= "<p style='text-align:center'><strong>coins: $coins buried: $buried_coins</strong></p></center>";

	if($joke_on == 1) {
	$fbml .= "<fb:visible-to-user uid=\"$user\"><center><a style='padding-bottom: 0px; margin-bottom:0px; font-size:75%' href='CHANGEME/settings.php'>hide jokes</a></center></fb:visible-to-user><div id='lol'><div style='margin:5px; border: 1px dotted black'><p style='text-align:center; font-size:125%'><strong>Q:  </strong>$question</p>
</div>";

	$fbml .= "<div style='margin:5px; border: 1px dotted black'><p style='text-align:center; font-size:125%'><strong>A:  </strong>$answer</p></div></div>";



	$fbml .="<center><form><input class=\"inputsubmit\" type=\"submit\"";
    $fbml .= "clickrewriteurl=\"$base_url/new.php\" clickrewriteid=\"lol\" value=\"ARRRRRR\"/></form></center>";

    }
//print $fbml;
return $fbml;
	//$facebook->api_client->profile_setFBML($fbml, $user);   
///return "<textarea>". $fbml ."<textarea>";
}



function set_profile($user) {
	global $network_id, $facebook, $facebook_canvas_url, $base_url;
	if($network_id == 1) {
		return;
	}
	
	$level = get_level($user);
	//$type = get_type_for($user);
	//$type_name = get_type_name_for($user);
	$team = ucwords(get_team($user));
	$coins = number_format(get_coin_total($user));
        $buried_coins = number_format(get_coin_total_buried($user));
	global $DB, $memcache;
	
	$joke_on = get_joke_on($user);
	
	if($joke_on == 1) {
		$ra = rand(1,get_total_joke_count());
		$r = $DB->GetRow('select * from jokes_approved where id = ?', array($ra));

	$question =$r['question'];
	$answer =$r['answer'];
	//$user =$r['user'];
	}
	
	
	$use_old_image = get_use_old_image($user);
	
	if($use_old_image == 0) {
	
		$gender = get_gender($user);
		if($gender == 'f') {
			$genderlong = 'female';
		}
		else {
			$genderlong = 'male';
		}

		if($level > 200) {
			$levelrank = 3;
		}
		else if($level > 50) {
			$levelrank = 2;
		}
	
		else {
			$levelrank =1;
		}
		$imagefile = "pirate_" . $genderlong . "_" . $levelrank .".jpg";
	}
	else {
		$imagefile = 'Piratey.jpg';
	}
	
	//$type_name_plural = get_type_name_plural_for($user);
	$fbml = "<center><h1 style=\"text-align:center\">Shiver me timbers!</h1>";
	if($use_old_image == 0) {
		$fbml .= "<a href='$facebook_canvas_url/?i=$user'><img height='300px' src='$base_url/images/a/$imagefile'></a><br>";
	}
	else {
		$fbml .= "<a href='$facebook_canvas_url/?i=$user'><img width='200px' src='$base_url/images/a/$imagefile'></a><br>";
	
	}
	
	$fbml .= "It's Cap'n <fb:name useyou='false' uid='$user'/></h1>";
	$fbml .="<h2 style='text-align:center'>A Level $level $team Pirate</h2>";
	$fbml .= "<p style='text-align:center'><strong>coins: $coins buried: $buried_coins</strong></p></center>";

	if($joke_on == 1) {
	$fbml .= "<fb:visible-to-user uid=\"$user\"><center><a style='padding-bottom: 0px; margin-bottom:0px; font-size:75%' href='CHANGEME/settings.php'>hide jokes</a></center></fb:visible-to-user><div id='lol'><div style='margin:5px; border: 1px dotted black'><p style='text-align:center; font-size:125%'><strong>Q:  </strong>$question</p>
</div>";

	$fbml .= "<div style='margin:5px; border: 1px dotted black'><p style='text-align:center; font-size:125%'><strong>A:  </strong>$answer</p></div></div>";



	$fbml .="<center><form><input class=\"inputsubmit\" type=\"submit\"";
    $fbml .= "clickrewriteurl=\"$base_url/new.php\" clickrewriteid=\"lol\" value=\"ARRRRRR\"/></form></center>";

    }
//print $fbml;
	$facebook->api_client->profile_setFBML($fbml, $user);   
///return "<textarea>". $fbml ."<textarea>";
}

function get_die_roll_image($rollNumber) {
	global $dice_Image1, $dice_Image2, $dice_Image3, $dice_Image4, $dice_Image5, $dice_Image6;
	if($rollNumber == 1) {
		return $dice_Image1;
	}
	else if($rollNumber == 2) {
		return $dice_Image2;
	}
	else if($rollNumber == 3) {
		return $dice_Image3;
	}
	else if($rollNumber == 4) {
		return $dice_Image4;
	}
	else if($rollNumber == 5) {
		return $dice_Image5;
	}
	else if($rollNumber == 6) {
		return $dice_Image6;
	}
	else {
		return $dice_Image1;
	}
}

function image_weather_type_match($weatherType) {
	global $corsair_hurricane, $corsair_lightcloud, $corsair_lightning, $corsair_stormycloud, $corsair_sun, $corsair_suncloud, $bucaneer_hurricane, $bucaneer_lightcloud, $bucaneer_lightning, $bucaneer_stormycloud, $bucaneer_sun, $bucaneer_suncloud, $barbary_hurricane, $barbary_lightcloud, $barbary_lightning, $barbary_stormycloud, $barbary_sun, $barbary_suncloud;

	if($weatherType == $corsair_hurricane or $weatherType == $corsair_lightcloud or $weatherType == $corsair_lightning or $weatherType == $corsair_stormycloud or $weatherType == $corsair_sun or $weatherType == $corsair_suncloud)
	{
		return "corsair";
	}
	else if($weatherType == $bucaneer_hurricane or $weatherType == $bucaneer_lightcloud or $weatherType == $bucaneer_lightning or $weatherType == $bucaneer_stormycloud or $weatherType == $bucaneer_sun or $weatherType == $bucaneer_suncloud)
	{
		return "buccaneer";
	}
	else {
		return "barbary";
	}
}

function get_ship_image_and_weather($type, $user, $noBadThings)
{
	global $corsair_hurricane, $corsair_lightcloud, $corsair_lightning, $corsair_stormycloud, $corsair_sun, $corsair_suncloud, $bucaneer_hurricane, $bucaneer_lightcloud, $bucaneer_lightning, $bucaneer_stormycloud, $bucaneer_sun, $bucaneer_suncloud, $barbary_hurricane, $barbary_lightcloud, $barbary_lightning, $barbary_stormycloud, $barbary_sun, $barbary_suncloud;
	$randomNumber = rand(1,1000);
	$repeatWeather = rand(1,10);
	$weatherType = get_weather($user);

	if($type == "corsair") {
		if($weatherType == "") {  //default
			$returnImage = $corsair_sun;
		}
		else if($weatherType == $corsair_hurricane or $weatherType == $corsair_lightning) { //after a storm
			$returnImage = $corsair_lightcloud;
		}
		else {
			if($weatherType ==  $corsair_stormycloud) {
				$stormOn = rand(1,10);
				if($stormOn < 2) {
					if($noBadThings == true) {
						$returnImage = $corsair_lightcloud;
					}
					else {
						$returnImage = $corsair_hurricane;
					}
				}
				else if($stormOn < 6) {
					if($noBadThings == true) {
						$returnImage = $corsair_lightcloud;
					}
					else {
						$returnImage = $corsair_lightning;
					}
				}
				else {
					$returnImage = $corsair_lightcloud;
				}
			}
			else if($repeatWeather < 7) {  //it's very likely to remain as it's previous weather type
				if(image_weather_type_match($weatherType) == $type) {
					$returnImage = $weatherType;
				}
				else {
					$returnImage = $corsair_sun;
				}
			}
			else {
				if($randomNumber < 2) {
					if($noBadThings == true) {
						$returnImage = $corsair_lightcloud;
					}
					else {
						$returnImage = $corsair_hurricane;
					}
				}
				else if($randomNumber < 400) {
					$returnImage = $corsair_lightcloud;
				}
				else if($randomNumber < 402) {
					if($noBadThings == true) {
						$returnImage = $corsair_lightcloud;
					}
					else {
						$returnImage = $corsair_lightning;
					}
				}
				else if($randomNumber < 550) {
					$returnImage = $corsair_stormycloud;
				}
				else if($randomNumber < 750) {
					$returnImage = $corsair_sun;
				}
				else if($randomNumber <= 1000) {
					$returnImage = $corsair_suncloud;
				}
			}
		}
	}
	else if($type == "buccaneer") {
		if($weatherType == "") {  //default
			$returnImage = $bucaneer_sun;
		}
		else if($weatherType == $bucaneer_hurricane or $weatherType == $bucaneer_lightning) { //after a storm
			$returnImage = $bucaneer_lightcloud;
		}
		else {
			if($weatherType ==  $bucaneer_stormycloud) {
				$stormOn = rand(1,10);
				if($stormOn < 2) {
					if($noBadThings == true) {
						$returnImage = $bucaneer_lightcloud;
					}
					else {
						$returnImage = $bucaneer_hurricane;
					}
				}
				else if($stormOn < 6) {
					if($noBadThings == true) {
						$returnImage = $bucaneer_lightcloud;
					}
					else {
						$returnImage = $bucaneer_lightning;
					}
				}
				else {
					$returnImage = $bucaneer_lightcloud;
				}
			}
			else if($repeatWeather < 7) {
				if(image_weather_type_match($weatherType) == $type) {
					$returnImage = $weatherType;
				}
				else {
					$returnImage = $bucaneer_sun;
				}
			}
			else {
				if($randomNumber < 2) {
					if($noBadThings == true) {
						$returnImage = $bucaneer_lightcloud;
					}
					else {
						$returnImage = $bucaneer_hurricane;
					}
				}
				else if($randomNumber < 400) {
					$returnImage = $bucaneer_lightcloud;
				}
				else if($randomNumber < 402) {
					if($noBadThings == true) {
						$returnImage = $bucaneer_lightcloud;
					}
					else {
						$returnImage = $bucaneer_lightning;
					}
				}
				else if($randomNumber < 550) {
					$returnImage = $bucaneer_stormycloud;
				}
				else if($randomNumber < 750) {
					$returnImage = $bucaneer_sun;
				}
				else if($randomNumber <= 1000) {
					$returnImage = $bucaneer_suncloud;
				}
			}
		}
	}
	else { //barbary
		if($weatherType == "") {  //default
			$returnImage = $barbary_sun;
		}
		else if($weatherType == $barbary_hurricane or $weatherType == $barbary_lightning) { //after a storm
			$returnImage = $barbary_lightcloud;
		}
		else {
			if($weatherType ==  $barbary_stormycloud) {
				$stormOn = rand(1,10);
				if($stormOn < 2) {
					if($noBadThings == true) {
						$returnImage = $barbary_lightcloud;
					}
					else {
						$returnImage = $barbary_hurricane;
					}
				}
				else if($stormOn < 6) {
					if($noBadThings == true) {
						$returnImage = $barbary_lightcloud;
					}
					else {
						$returnImage = $barbary_lightning;
					}
				}
				else {
					$returnImage = $barbary_lightcloud;
				}
			}
			else if($repeatWeather < 7) {
				if(image_weather_type_match($weatherType) == $type) {
					$returnImage = $weatherType;
				}
				else {
					$returnImage = $barbary_sun;
				}
			}
			else {
				if($randomNumber < 2) {
					if($noBadThings == true) {
						$returnImage = $barbary_lightcloud;
					}
					else {
						$returnImage = $barbary_hurricane;
					}
				}
				else if($randomNumber < 400) {
					$returnImage = $barbary_lightcloud;
				}
				else if($randomNumber < 402) {
					if($noBadThings == true) {
						$returnImage = $barbary_lightcloud;
					}
					else {
						$returnImage = $barbary_lightning;
					}
				}
				else if($randomNumber < 550) {
					$returnImage = $barbary_stormycloud;
				}
				else if($randomNumber < 750) {
					$returnImage = $barbary_sun;
				}
				else if($randomNumber <= 1000) {
					$returnImage = $barbary_suncloud;
				}
			}
		}
	}
	set_weather($user, $returnImage);
	
	return $returnImage;
}

function get_weather($user) {
	global $memcache;
	if($memcache) {
		$s = $memcache->get($user . ":w");
		
		if($s == "") {
			return ""; //not set, default to 0 miles sailed
		}
		
		return $s;
	}
	else { //I don't believe this should ever happen
			return "";
	}
}

function set_weather($user, $weather) {
	global $memcache;

	if($memcache) {
		$s = $memcache->set($user . ":w", $weather);
	}
	return $s;
}

function processes_weather_effects($user, $ship_image_blue)
{
	global $barbary_hurricane, $corsair_hurricane, $bucaneer_hurricane, $barbary_lightning, $corsair_lightning, $bucaneer_lightning;
	
	if($ship_image_blue == $barbary_hurricane or $ship_image_blue == $corsair_hurricane or $ship_image_blue == $bucaneer_hurricane)
	{
		$sextant_count = get_sextant_count($user);
		if($sextant_count >= 1) {
			$success = use_sextant($user);  //should always pass, ignore success
			$msg = "Using your Sextant you were able to navigate through the hurricane!<br>Sextant -1";
		}
		else {
			$survives = get_ship_survives_hurricane($user);
			if($survives == false) {
				$damage = set_hurricane_damage($user);
				$msg = "Your ship was damaged by a massive hurricane!<br>Health -$damage";
			}
			else {
				$levelUp = lower_level_up($user);
				if($levelUp == true) {
					$msg = "You were hit by a hurricane but you survived!<br>Your skills on the sea have increased.<br>Level +1";
				}
				else {
					$msg = "Watch out for hurricanes at sea!<br>You barely made it through this one.";
				}
			}
		}
	}
	else if($ship_image_blue == $barbary_lightning or $ship_image_blue == $corsair_lightning or $ship_image_blue == $bucaneer_lightning)
	{
		$sextant_count = get_sextant_count($user);
		if($sextant_count >= 1) {
			$success = use_sextant($user);  //should always pass, ignore success
			$msg = "Using your Sextant you were able to navigate through the storm!<br>Sextant -1";		
		}
		else {
			$survives = get_ship_survives_storm($user);
			if($survives == false) {
				$damage = set_storm_damage($user);
				$msg = "Your ship was damaged by a massive storm!<br>Health -$damage";
			}
			else {
				$levelUp = lower_level_up($user);
				if($levelUp == true) {
					$msg = "You were hit by a storm but you survived!<br>Your skills on the sea have increased.<br>Level +1";
					log_levels($user, 'level up from storm');

				}
				else {
					$msg = "Watch out for storms at sea!<br>You barely made it through this one.";
				}
			}
		}
	}
	else { // no danger case
		$msg = "";
	}
	return $msg;
}

function set_storm_damage($user)
{
	$health = get_health($user);
	$level = get_level($user);

	$damage = round($level*.15);
	if($damage >= $health) {
		$damage = $health;
		set_damage($user, $level);
	}
	else {
		set_damage($user, (get_damage($user) + $damage));
	}
	
	return $damage;
}

function set_hurricane_damage($user)
{
	$health = get_health($user);
	$level = get_level($user);

	$damage = round($level*.25);
	if($damage >= $health) {
		$damage = $health;
		set_damage($user, $level);
	}
	else {
		set_damage($user, (get_damage($user) + $damage));
	}
	
	return $damage;
}

function get_ship_survives_hurricane($user)
{
	$level = get_level($user);
	$upgrades = get_upgrades($user);

	foreach($upgrades as $key=>$value) {
		$upgrade_name = $value['upgrade_name'];
		$level = $value['level'];
		if($upgrade_name == 'cannons') {
			$cannon_level = $level;
		}
		else if($upgrade_name == 'sails') {
			$sail_level = $level;
		}
		else if($upgrade_name == 'hull') {
			$hull_level = $level;
		}
		else if($upgrade_name == 'crew') {
			$crew_level = $level;
		}
	}


	if(!isset($crew_level)) {
		$crew_level = 0;
	}	

	if(!isset($hull_level)) {
		$hull_level = 0;
	}
	if(!isset($cannon_level)) {
		$cannon_level = 0;
	}
	if(!isset($sail_level)) {
		$sail_level = 0;
	}

	//200 is the cap
	if($crew_level > 200) {
		$crew_level = 200;
	}
	if($hull_level > 200) {
		$hull_level = 200;
	}
	$health = get_health($user);
	$level = get_level($user);
	
	if($level == 0) {
		$health = 1;
		$level = 1;
	}
	
	$survival = (.35 * ($hull_level/125)) + (.35 * ($crew_level/100)) + (.30 * ($health/$level));
	$survival = $survival * 100;
	$survival = round($survival);
	if($survival > 66) {
		$survival = 75;
	}
	$random = rand(1,100);
	
	if($random <= $survival) {
		return true;
	}
	else {
		return false;
	}
}

function get_ship_survives_storm($user)
{
	$level = get_level($user);
	$upgrades = get_upgrades($user);

	foreach($upgrades as $key=>$value) {
		$upgrade_name = $value['upgrade_name'];
		$level = $value['level'];
		if($upgrade_name == 'cannons') {
			$cannon_level = $level;
		}
		else if($upgrade_name == 'sails') {
			$sail_level = $level;
		}
		else if($upgrade_name == 'hull') {
			$hull_level = $level;
		}
		else if($upgrade_name == 'crew') {
			$crew_level = $level;
		}
	}


	if(!isset($crew_level)) {
		$crew_level = 0;
	}	

	if(!isset($hull_level)) {
		$hull_level = 0;
	}
	if(!isset($cannon_level)) {
		$cannon_level = 0;
	}
	if(!isset($sail_level)) {
		$sail_level = 0;
	}
	
	//200 is the cap
	if($crew_level > 200) {
		$crew_level = 200;
	}
	if($sail_level > 200) {
		$sail_level = 200;
	}
	$health = get_health($user);
	$level = get_level($user);

	if($level == 0) {
		$health = 1;
		$level = 1;
	}
	
	$survival = (.35 * ($sail_level/125)) + (.35 * ($crew_level/100)) + (.30 * ($health/$level));
	$survival = $survival * 100;
	$survival = round($survival);
	if($survival > 80) {
		$survival = 80;
	}
	$random = rand(1,100);
	if($random <= $survival) {
		return true;
	}
	else {
		return false;
	}
}

function lower_level_up($user)
{
	$level = get_level($user);
	$ra = rand(1, $level);
    
    if($ra < 100 ) {
    
        set_level($user, $level + 1);
        $level_up = true;
    }
    else {
        $level_up = false;
    }
    
    return $level_up;
}

function get_booty_data_from_id($id)
{	//this sucks
	global $item_goldbar_large, $item_goldbar_small, $item_message_bottle_large, $item_messsage_bottle_small, $item_dynamite_large, 
		$item_dynamite_small, $item_flag_large, $item_flag_small, $item_bomb_large, $item_bomb_small, $item_monkey_large, $item_monkey_small, 
		$item_rum_large, $item_rum_small, $item_sextant_large, $item_sextant_small, $item_spyglass_large, $item_spyglass_small, 
		$item_parrot_large, $item_parrot_small, $item_pistol_large, $item_pistol_small, $item_sword_large, $item_sword_small, $item_treasuremap_large, 
		$item_treasuremap_small, $item_ham_small, $item_ham_large, $item_bomb_50, $item_dynamite_50, $item_flag_50, $item_gold_bars_50, $item_ham_50, 
		$item_map_50, $item_message_bottle_50, $item_monkey_50, $item_parrot_50, $item_pistol_50, $item_rum_50, $item_sextant_50, $item_sword_50;

	if($id == 1) {
		$booty_name = 'Treasure Map';
		$booty_thumb_image = $item_treasuremap_small;
		$booty_large_image = $item_treasuremap_small;
		$booty_50 = $item_map_50;
	}
	else if($id == 2) {
		$booty_name = 'Gold Bars';
		$booty_thumb_image = $item_goldbar_small;
		$booty_large_image = $item_goldbar_large;
		$booty_50 = $item_gold_bars_50;
	}
	else if($id == 3) {
		$booty_name = 'Message in a Bottle';
		$booty_thumb_image = $item_messsage_bottle_small;
		$booty_large_image = $item_message_bottle_large;
		$booty_50 = $item_message_bottle_50;
	}
	else if($id == 4) {
		$booty_name = 'Dynamite';
		$booty_thumb_image = $item_dynamite_small;
		$booty_large_image = $item_dynamite_large;
		$booty_50 = $item_dynamite_50;
	}
	else if($id == 5) {
		$booty_name = 'Pirate Flag';
		$booty_thumb_image = $item_flag_small;
		$booty_large_image = $item_flag_large;
		$booty_50 = $item_flag_50;
	}
	else if($id == 6) {
		$booty_name = 'Bomb';
		$booty_thumb_image = $item_bomb_small;
		$booty_large_image = $item_bomb_large;
		$booty_50 = $item_bomb_50;
	}
	else if($id == 7) {
		$booty_name = 'Monkey';
		$booty_thumb_image = $item_monkey_small;
		$booty_large_image = $item_monkey_large;
		$booty_50 = $item_monkey_50;
	}
	else if($id == 8) {
		$booty_name = 'Pistol';
		$booty_thumb_image = $item_pistol_small;
		$booty_large_image = $item_pistol_large;
		$booty_50 = $item_pistol_50;
	}
	else if($id == 9) {
		$booty_name = 'Rum';
		$booty_thumb_image = $item_rum_small;
		$booty_large_image = $item_rum_large;
		$booty_50 = $item_rum_50;
	}
	else if($id == 10) {
		$booty_name = 'Sword';
		$booty_thumb_image = $item_sword_small;
		$booty_large_image = $item_sword_large;
		$booty_50 = $item_sword_50;
	}	
	else if($id == 11) {
		$booty_name = 'Parrot';
		$booty_thumb_image = $item_parrot_small;
		$booty_large_image = $item_parrot_large;
		$booty_50 = $item_parrot_50;
	}
	else if($id == 12) {
		$booty_name = 'Salted Ham';
		$booty_thumb_image = $item_ham_small;
		$booty_large_image = $item_ham_large;
		$booty_50 = $item_ham_50;	
	}
	else if($id == 13) {
		$booty_name = 'Sextant';
		$booty_thumb_image = $item_sextant_small;
		$booty_large_image = $item_sextant_large;
		$booty_50 = $item_sextant_50;	
	}	
	else {
		$booty_name = "Unknown Item";
		$booty_thumb_image = 0;
		$booty_large_image = 0;
		$booty_50 = 0;
	}

return array($booty_name, $booty_thumb_image, $booty_large_image, $booty_50);

}

function get_upgrade_costs() {
 global $memcache;

// $memcache->set("upgrade_cost", $info, FALSE, 3);
 
 $info = $memcache->get("upgrade_cost");
 if($info == false) {
 	$r_one = rand(1,3);
 	$r_two = rand(1,3);
 	$r_three = rand(1,3);
 	
 	$info = array($r_one, $r_two, $r_three);
 	
 	$memcache->set("upgrade_cost", $info, FALSE, 300);	
 }
	
	return $info;

 
}

function get_booty_info($stuff_id) {
  global $memcache, $DB;
  if($memcache) {
	  $booty_info = $memcache->get("bty2" . $stuff_id);
    
    if($booty_info == false) {
    		$booty_info = $DB->GetOne("select info from stuff_info where stuff_id = $stuff_id");
			$memcache->set("bty2" . $stuff_id, $booty_info);
		}
  }
  else {
		$booty_info = $DB->GetOne("select info from stuff_info where stuff_id = $stuff_id");	
  }
  return $booty_info;
}

function get_pirate_tip()
{
	$randomNumber = rand(1,13);
	if($randomNumber == 1) {
		return "Pirate tip: Watch out for warning clouds, a storm may be brewing!";
	}
	else if($randomNumber == 2) {
		return "Pirate tip: Check out the tavern in the harbor to throw some bones.";
	}
	else if($randomNumber == 3) {
		return "Pirate tip: Save your coins to purchase ship upgrades at the shipyard.";
	}
	else if($randomNumber == 4) {
		return "Pirate tip: Be sure to bury your treasure often so pirates don't steal it!";
	}
	else if($randomNumber == 5) {
		return "Pirate tip: Trade in special items at the harbor to raise your pirate level.";
	}
	else if($randomNumber == 6) {
		return "Pirate tip: Recruit more friends to raise your pirate level.";
	}
	else if($randomNumber == 7) {
		return "Pirate tip: Special items can be purchased in the harbor.";
	}
	else if($randomNumber == 8) {
		return "Pirate tip: Special items can be purchased at the harbor.";
	}
	else if($randomNumber == 9) {
		return "Pirate tip: Storms can pop-up when you least expect them.";
	}
	else if($randomNumber == 10) {
		return "Pirate tip: If you bury coins you must dig them up before you can use them.";
	}
	else if($randomNumber == 11) {
		return "Pirate tip: Race your parrots and monkey in the tavern.";
	}	
	else if($randomNumber == 12) {
		return "Pirate tip: If you sail in safe waters you won't find any enemy towns to pillage.";
	}	
	else if($randomNumber == 13) {
		return "Pirate tip: Explore enemy waters with upgraded sails if you want to use treasure maps!";
	}
	else {
		return "Yo ho, Yo ho, a pirates life for me!";
	}
}

function get_house_array($houseCount, $select_all) {
	global $pillage_house1, $pillage_house2, $image_uid;
	//all the different houses positions
	$houseArray[0] = "<div style=\"position:absolute;left:70px;top:180px;\">" . image_return($pillage_house1) . "<input type=\"checkbox\" value=\"house\" name=\"house";
	$houseArray[1] = "<div style=\"position:absolute;left:385px;top:95px;\">" . image_return($pillage_house1) . "<input type=\"checkbox\" value=\"house\" name=\"house";
	$houseArray[2] = "<div style=\"position:absolute;left:240px;top:130px;\">" . image_return($pillage_house2) . "<input type=\"checkbox\" value=\"house\" name=\"house";
	$houseArray[3] = "<div style=\"position:absolute;left:130px;top:360px;\">" . image_return($pillage_house1) . "<input type=\"checkbox\" value=\"house\" name=\"house";
	$houseArray[4] = "<div style=\"position:absolute;left:410px;top:250px;\">" . image_return($pillage_house2) . "<input type=\"checkbox\" value=\"house\" name=\"house";
	$houseArray[5] = "<div style=\"position:absolute;left:345px;top:335px;\">" . image_return($pillage_house2) . "<input type=\"checkbox\" value=\"house\" name=\"house";
	$houseArray[6] = "<div style=\"position:absolute;left:245px;top:370px;\">" . image_return($pillage_house1) . "<input type=\"checkbox\" value=\"house\" name=\"house";
	$houseArray[7] = "<div style=\"position:absolute;left:495px;top:305px;\">" . image_return($pillage_house2) . "<input type=\"checkbox\" value=\"house\" name=\"house";
	$houseArray[8] = "<div style=\"position:absolute;left:180px;top:230px;\">" . image_return($pillage_house1) . "<input type=\"checkbox\" value=\"house\" name=\"house";
	$houseArray[9] = "<div style=\"position:absolute;left:305px;top:230px;\">" . image_return($pillage_house1) . "<input type=\"checkbox\" value=\"house\" name=\"house";
	$houseArray[10] = "<div style=\"position:absolute;left:170px;top:155px;\">" . image_return($pillage_house1) . "<input type=\"checkbox\" value=\"house\" name=\"house";
	$houseArray[11] = "<div style=\"position:absolute;left:360px;top:170px;\">" . image_return($pillage_house1) . "<input type=\"checkbox\" value=\"house\" name=\"house";
	$houseArray[12] = "<div style=\"position:absolute;left:270px;top:300px;\">"  . image_return($pillage_house1) . "<input type=\"checkbox\" value=\"house\" name=\"house";
	$houseArray[13] = "<div style=\"position:absolute;left:420px;top:375px;\">" . image_return($pillage_house1) . "<input type=\"checkbox\" value=\"house\" name=\"house";
	$houseArray[14] = "<div style=\"position:absolute;left:320px;top:100px;\">" . image_return($pillage_house2) . "<input type=\"checkbox\" value=\"house\" name=\"house";


	for($i = 0; $i < $houseCount; $i++) {
		$randomHouseNumber = rand(0,14 - $i);
		$randomHouses[$i] = $houseArray[$randomHouseNumber];
		$randomHouses[$i] .= $i + 1;
		$randomHouses[$i] .= "\"";
		if($select_all == true) {
			$randomHouses[$i] .= "checked";	
		}
		$randomHouses[$i] .= "></div>";
		$houseArray = array_remove($houseArray, $randomHouseNumber);
		
	}
	
	return $randomHouses;
}

function get_house_count($user) {
	
	$upgrades = get_upgrades($user);

	foreach($upgrades as $key => $value) {
		$upgrade_name = $value['upgrade_name'];
		$level = $value['level'];
		if($upgrade_name == 'crew') {
			$crew_level = $level;
		}
	}
	
	if($crew_level <= 15) {
		return $crew_level;
	}
	else {
		return 15;
	}
}

function assign_items_to_houses($houseCount) {
	if($houseCount >= 8) {
		//always at least 1 item
		return rand(1, $houseCount);
	}
	else if(rand(1,100) > 40) {
		//60% chance of an item
		return rand(1, $houseCount);
	}
	else {
		return 0;
	}
}

//have to use memcache so people can't cheat and view source to see which house has booty
function set_item_to_memcache($user, $houseNumber) {
	global $memcache;
	if($memcache) {
		$memcache->set("itm:" . $user, $houseNumber);
	}
}

function get_item_from_memcache($user) {
	global $memcache;
	if($memcache) {
		return $memcache->get("itm:" . $user);
	}
	else {
		return 0;
	}
}

//can use with either a key or index
function array_remove($array, $key_index) {
    if(is_array($array)) {
        unset($array[$key_index]);   
        if(gettype($key_index) != "string") {
               $temparray = array();
               $i = 0;
               foreach($array as $value) {
                    $temparray[$i] = $value;
                   $i++;
               }
               $array = $temparray;
           }
        return $array;
    }
    else {
         return false;
    }
}

function get_crew_per_house($user, $houseCount) {
	$upgrades = get_upgrades($user);

	foreach($upgrades as $key => $value) {
		$upgrade_name = $value['upgrade_name'];
		$level = $value['level'];
		if($upgrade_name == 'crew') {
			$crew_level = $level;
		}
	}
	
	return round($houseCount / $crew_level);
}

function get_winnings_per_house($user, $houseCount, $houseList, $houseItem, $crew_per_house) {
	$chance = 60;
	if($crew_per_house == 1) {
		$chance = 60;
	}
	else if($crew_per_house == 2) {
		$chance = 63;
	}
	else if($crew_per_house == 3) {
		$chance = 65;
	}
	else if($crew_per_house == 4) {
		$chance = 68;
	}
	else if($crew_per_house == 5) {
		$chance = 70;
	}
	else if($crew_per_house == 6) {
		$chance = 73;
	}
	else if($crew_per_house == 7) {
		$chance = 75;
	}
	else if($crew_per_house == 8) {
		$chance = 78;
	}
	else if($crew_per_house == 9) {
		$chance = 80;
	}
	else if($crew_per_house == 10) {
		$chance = 83;
	}
	else if($crew_per_house > 10) {
		$chance = 85;
	}
	
	for($i = 0; $i < $houseCount; $i++) {
		if($houseList[$i] == "house") {  //if the house has in fact been attacked
			if(rand(1,100) <= $chance) {
				if(($i + 1) == $houseItem) {
					//win the item
					if(rand(1,100) < 60) {
						$result_list[$i] = "item";
					}
					else {
						$result_list[$i] = "gold";
					}
				}
				else {
					//win gold
					$result_list[$i] = "gold";
				}
			}
			else {
				$result_list[$i] = "failed";
				//echo "attack fails";
			}
		}
		else {
			$result_list[$i] = "nothing";
			//echo "nothing";
		}
	}
	
	return $result_list;
}


function grant_item($user) {
	$ra = rand(1, 145);
if($ra < 5) {
//	$booty = "a treasure map";
//	$booty_pic = $treasure_map_image;
	$stuff_id = 1;
}
else if($ra < 30) {
//	$booty = "a bomb";
//	$booty_pic = $bomb_image;
	$stuff_id = 6;
}
else if($ra < 40) {
//	$booty = "some gold bars";
//	$booty_pic = $gold_image;
	$stuff_id = 2;
}
else if($ra < 55) {
//	$booty = "a message in a bottle";
//	$booty_pic = $message_in_a_bottle_image;
	$stuff_id = 3;
}
else if($ra < 60) {
//	$booty = "some dynamite";
//	$booty_pic = $dynamite_image;
	$stuff_id = 4;
}
else if($ra < 75) {
//	$booty = "a pirate flag";
//	$booty_pic = $flag_image;
	$stuff_id = 5;
}
//else if($ra < 78) {   			//not monkeys til we get a use for them
//	$booty = "a monkey";
//	$booty_pic = $monkey_350;
//	$stuff_id = 7;
//}
else if($ra < 80) {
//	$booty = "a pistol";
//	$booty_pic = $pistol_350;
	$stuff_id = 8;
}
else if($ra < 84) {
//	$booty = "a sword";
//	$booty_pic = $sword_350;
	$stuff_id = 10;
}
//else if($ra < 86) {    		//no parrots til we use them
//	$booty = "a parrot";
//	$booty_pic = $item_parrot_large;
//	$stuff_id = 11;
//}
else if($ra < 110) {
//	$booty = "some rum";
//	$booty_pic = $rum_350;
	$stuff_id = 9;
}
else if($ra < 130) {  //ham
	$stuff_id = 12;
}
else if($ra < 145) {  //sextant
	$stuff_id = 13;
}

increment_booty($user, $stuff_id);

return $stuff_id;
}

function processes_weather_effects2($user, $ship_image_blue)
{
	global $lightning;

	if($ship_image_blue == $lightning)
	{
		$sextant_count = get_sextant_count($user);
		if($sextant_count >= 1) {
			$success = use_sextant($user);  //should always pass, ignore success
			$msg = "Using your Sextant you were able to navigate through the storm!<br>Sextant -1";		
		}
		else {
			$survives = get_ship_survives_storm($user);
			if($survives == false) {
				$damage = set_storm_damage($user);
				$msg = "Your ship was damaged by a massive storm!<br>Health -$damage";
			}
			else {
				$levelUp = lower_level_up($user);
				if($levelUp == true) {
					$msg = "You were hit by a storm but you survived!<br>Your skills on the sea have increased.<br>Level +1";
				}
				else {
					$msg = "Watch out for storms at sea!<br>You barely made it through this one.";
				}
			}
		}
	}
	else { // no danger case
		$msg = "";
	}
	return $msg;
}

//not called anymore?
function update_database_sell_item($user, $uid, $price) {
	global $DB;

//	$sql = "insert into items_sell (uid, id, stuff_id, price, created_at) values(?, ?, ?, ?, now()) on duplicate key update price = $price, created_at = now()";
	$sql = "update items_sell set price = $price, created_at = now() where uid = ?";
	
	try {
		$result = $DB->Execute($sql, array($uid));
	}
	catch (exception $e) {
		return false; //purhaps it sold before you could update it
	}
	return true;
}

function sell_item($user, $item_id, $price) {
	global $memcache, $DB;
	
	$sql = "insert into items_sell (id, stuff_id, price, created_at) values(?, ?, ?, now()) on duplicate key update price = $price, created_at = now()";
	
	$result = $DB->Execute($sql, array($user, $item_id, $price));
	$sale_id = $DB->GetOne("select last_insert_id()");  //get auto increment of last inserted item

	decrement_booty($user, $item_id);  //take the item out of their inventory

	$success = update_memcache_sell_item_indexing($user, $sale_id, FALSE, $price);
	
	if($success == FALSE) {
		//can't display store, no memcache worky
	}
	
	update_sale_stats_posting($sale_id, $item_id, $price, $user);
	
	return $sale_id;
}

function update_sale_stats_posting($uid, $item_id, $price, $seller) {
	global $memcache, $DB;
	
	$sql = "insert into items_posted_table (uid, item_id, seller, price, created_at) values(?, ?, ?, ?, now());";

	$result = $DB->Execute($sql, array($uid, $item_id, $seller, $price));
}

function update_sale_stats_selling($uid, $item_id, $price, $seller, $buyer) {
	global $memcache, $DB;
	
	$sql = "insert into items_sold_table (uid, item_id, seller, buyer, price, created_at) values(?, ?, ?, ?, ?, now());";

	$result = $DB->Execute($sql, array($uid, $item_id, $seller, $buyer, $price));		
}

function get_stuff_id_from_uid($user, $uid) {
	global $memcache, $DB;
	
	$sql = 'select stuff_id from items_sell where uid = ?';
	try {
		$stuff_id = $DB->GetOne($sql, array($uid));
	}
	catch (Exception $e) {
		return false;  //item already bought (doesn't exist)
	}
	
	if($stuff_id != false) {
		return $stuff_id;
	}
	
	return false;
}

function delete_sell_item($user, $uid, $increment_booty) {
	global $memcache, $DB;

	$stuff_id = get_stuff_id_from_uid($user, $uid);
	
	if($stuff_id == false) {
		return false;
	}

	//if the items being bought, don't increment the sellers booty
	//if the seller is canceling sale, then give item back
	if($increment_booty) {
		increment_booty($user, $stuff_id);  //put the item back in their inventory
	}
	
	//first remove from DB
	$sql = "delete from items_sell where uid = ?";
	try {
		$result = $DB->Execute($sql, array($uid));
	}
	catch (Exception $e) {
//		return false;
	}
	
	//now remove from memcache list
	update_memcache_sell_item_indexing($user, $uid, TRUE, 0);
	
	//now remove from buyers list
	if($memcache) {
		$memcache->set("b:" . $uid . ":" . $stuff_id . ":p", "");
		$memcache->set("b:" . $uid . ":" . $stuff_id . ":ca", ""); 
		$memcache->set("b:" . $uid . ":" . $stuff_id . ":u", "");
	}
	//update the index string
	$id_string = get_buy_indexing_string($stuff_id);
	if($id_string != false) { //if we couldn't find the buy_indexing_string
		$id_array = explode(",", $id_string);
		for($i = count($id_array); $i >= 0; $i--) {
			if($id_array[$i] == $uid) {
				array_splice($id_array, $i, 1); //remove the duplicate from the list
			}
		}
		$new_id_string = implode(",", $id_array);
		if($memcache) {
			$memcache->set("b:" . $stuff_id . ":isb", $new_id_string);
		}
		store_index_string_in_db($new_id_string, $stuff_id);
	}
	return true;
}

function update_db_and_memcache_buying($user, $uid) {
	global $memcache, $DB;

	try {
		$sql = 'select stuff_id, created_at, id, price from items_sell where uid = ?';
		$returned_array = $DB->GetArray($sql, array($uid));
	}
	catch (exception $e) {
		return false;  //item doesn't exist, already bought?
	}
	
	$stuff_id = $returned_array[0]['stuff_id'];
	$created_at = $returned_array[0]['created_at'];
	$id = $returned_array[0]['id'];
	$price = $returned_array[0]['price'];

	//now remove from buyers list
	if($memcache) {
		$memcache->set("b:" . $uid . ":" . $stuff_id . ":p", $price);
		$memcache->set("b:" . $uid . ":" . $stuff_id . ":ca", $created_at); 
		$memcache->set("b:" . $uid . ":" . $stuff_id . ":u", $id);
	}
	
	//put the item on the end, mysql will resort it later
	
	//update the index string
	$id_string = get_buy_indexing_string($stuff_id);
	$id_array = explode(",", $id_string);
	for($i = count($id_array); $i >= 0; $i--) {
		if($id_array[$i] == $uid) {
			array_splice($id_array, $i, 1); //remove the duplicate from the list
		}
	}
	$id_array[count($id_array)] = $uid;
	$new_id_string = implode(",", $id_array);
	if($memcache) {
		$memcache->set("b:" . $stuff_id . ":isb", $new_id_string);
	}
	store_index_string_in_db($new_id_string, $stuff_id);
	
	return true;
}

function update_memcache_sell_item_indexing($user, $sale_id, $is_delete, $price) {
	global $memcache, $DB;
	
	if($memcache) {
		$id_string = $memcache->get($user . ":sil"); //get sell item list
		if($id_string == false) {
			create_memcache_sell_items_from_db($user);
			return true;
		}
		else {
			$id_array = explode(",", $id_string);
			for($i = count($id_array); $i >= 0; $i--) {
				if($id_array[$i] == $sale_id) {
					array_splice($id_array, $i, 1); //remove the duplicate from the list
				}
			}
			if(!$is_delete) {
				$id_array[count($id_array)] = $sale_id; //append new item to end of array
				
				//update the other changed memcache values
				$memcache->set($user . ":" . $sale_id . ":silp", $price);
				
				//we don't have created_at so we need to fetch it before putting it into memcache
				$sql = 'select created_at from items_sell where uid = ?';
				$result = $DB->GetOne($sql, array($sale_id));
				$memcache->set($user . ":" . $sale_id . ":silca", $result);
			}
//			echo "Count: " . count($id_array) . "<br>";
			$new_id_string = implode(",", $id_array);
//			echo "New String: " . $new_id_string . "<br>";
			$memcache->set($user . ":sil", $new_id_string);
			return true;
		}
	}
	else {
		return false;
	}
}

function get_from_memcache($text) {
	global $memcache;
	
	if($memcache) {
		return $memcache->get($text);
	}
	else {
		return false;
	}
}

function get_sell_item_indexing_string($user) {
	global $memcache, $DB;
	
	if($memcache) {
		return $memcache->get($user . ":sil"); //get sell item list
	}
	else {
		return false;
	}
}

//if $use_item_array is true, be sure to pass in a real $item_array
function get_sell_item_result($user, $id_string, $index, $use_item_array, $item_array) {
	global $memcache, $DB;

	if($memcache) {
		//couldn't find it in memcache, use DB that was passed in
		if($use_item_array == true) {
			return $item_array[$index][$id_string];
		}
		else {
			return $memcache->get($user . ":" . $index . $id_string); //get sell item list
		}
	}
	else { //if memcache is down, use the DB
		return $item_array[$index]["$id_string"];
	}
}

function get_buy_indexing_string($item) {
	global $memcache, $DB;
	
	if($memcache) {
		$result = $memcache->get("b:" . $item . ":isb");
		if($result != false) {
			return $result;
		}
	}

	//else fetch from db
	$sql = "select index_string from buy_item_indexing where stuff_id = ?;";

	try {
		$result = $DB->GetOne($sql, array($item));
	}
	catch (exception $e) {
		return false;
	}
	
	if($memcache and $result != false) {
		$memcache->set("b:" . $item . ":isb", $result);
	}
	return $result;
}

function complete_buy_action($user, $uid) {
	global $api_key, $secret, $memcache, $DB, $facebook_canvas_url, $facebook, $base_url;
	
	$sql = "select id, stuff_id, price from items_sell where uid = ?";

	try {
		$item_array = $DB->GetArray($sql, array($uid));
	}
	catch (exception $e) {
		return "fail";  //item already purchased
	}
	
	if(count($item_array) == 0) {
		return "fail";
	}
	
	$seller_id = $item_array[0]['id'];
	$price = $item_array[0]['price'];
	$stuff_id = $item_array[0]['stuff_id'];
	$stuff_data_array = get_booty_data_from_id($stuff_id);
	$item_name = $stuff_data_array[0];

	$body = "<fb:name uid=$user' /> bought a $item_name at the <a href=\"$facebook_canvas_url/?i=$user\">Pirates Trading Post</a>!";
	$image_1 = "$base_url/images/flag_200.jpg";
	$image_1_link = "$facebook_canvas_url/?i=$user";
	$title = "bought an item..  Arrr..";
	
	$coins = get_coin_total($user);
	if($coins >= $price) {
		
		/*try {
			$re2 = $facebook->api_client->feed_publishActionOfUser($title, $body, $image_1, $image_1_link);
		} catch (FacebookRestClientException $fb_e) { }
		*/
		
		update_sale_stats_selling($uid, $stuff_id, $price, $seller_id, $user);
	
		$result = delete_sell_item($seller_id, $uid, false);
		if($result == false) {
			//item can't be deleted, maybe already purchased?
			return "fail";
		}
		set_coins($user, $coins - $price);
		log_coins($user, $price, "bought item from $seller_id");

		//put seller coins into buried so they can't be stolen right away
		set_coin_total_buried($seller_id, get_coin_total_buried($seller_id) + $price);
		log_coins($seller_id, -$price, "sold item to $user");
		increment_booty($user, $stuff_id);
		
		try {
			$sql = "select session_key from users where id = ?";
			$seller_session_key = $DB->GetOne($sql, array($seller_id));
		}
		catch (exception $e) {
			//maybe no longer in our db, ignore error
		}
	
		try {
			$facebook_new = new Facebook($api_key, $secret);
			$facebook_new->set_user($seller_id, $seller_session_key);
			$facebook_new->api_client->notifications_send(array($seller_id) , " just sold an item on the <a href='$facebook_canvas_url'>Pirates trading post</a>! A $item_name was sold to <fb:name uid='$user' firstnameonly='false' shownetwork='false' useyou='false' linked='true'/> for $price coins!", 'user_to_user');
		} catch (FacebookRestClientException $fb_e) {
			//do nothing here, might not have a valid infinite session key to notify the person
		}
		return "succeed";
	}
	else {
		return "no coins";
	}
}

//find the first item in buy memcache and return the price (first item is lowest price)
function lowest_buy_price($user, $stuff_id) {
	global $memcache;
	
	$id_string = get_buy_indexing_string($stuff_id);
	$id_array = explode(",", $id_string);
	
	for($i = 0; $i < count($id_array); $i++) {
		$price = $memcache->get("b:" . $id_array[$i] . ":" . $stuff_id . ":p");
		if($price != false) {
			return $price;
		}
	}
	return false;
}

//generates memcache entries for 1 item type, call this for each item to be bought
function generate_memcache_for_buying($user, $item_id) {
	global $memcache, $DB;
	
	$sql = 'select uid, id, stuff_id, price, created_at from items_sell where stuff_id = ? ORDER BY price ASC, created_at ASC limit 0, 1000;';
	$item_array = $DB->GetArray($sql, array($item_id));
	
	$sell_item_indexing = "";
	foreach($item_array as $key=>$value) {
		$uid = $value['uid'];
		$id = $value['id'];
		$stuff_id = $value['stuff_id'];
		$price = $value['price'];
		$created_at = $value['created_at'];
		if($memcache) {
			$memcache->set("b:" . $uid . ":" . $item_id . ":p", $price);
			$memcache->set("b:" . $uid . ":" . $item_id . ":ca", $created_at); 
			$memcache->set("b:" . $uid . ":" . $item_id . ":u", $id);
		}
		if($sell_item_indexing == "") {
			$sell_item_indexing = "$uid";
		}
		else {
			$sell_item_indexing .= ",$uid";
		}
	}
	if($memcache) {
		$memcache->set("b:" . $item_id . ":isb", $sell_item_indexing);
	}
	return array($item_array, $sell_item_indexing);	
}

function store_index_string_in_db($index_string, $item_id) {
	global $memcache, $DB;
	
	$sql = "insert into buy_item_indexing (stuff_id, index_string) values(?, ?) on duplicate key update index_string = \"$index_string\";";

	$result = $DB->Execute($sql, array($item_id, $index_string));	
}

//both creates the memcache stuff, but also returns the database results to just use those
function create_memcache_sell_items_from_db($user) {
	global $memcache, $DB;
	
	$sql = 'select uid, id, stuff_id, price, created_at from items_sell where id = ? ORDER BY created_at;';
	$item_array = $DB->GetArray($sql, array($user));
		
	$sell_item_indexing = "";
	foreach($item_array as $key=>$value) {
		$uid = $value['uid'];
		$stuff_id = $value['stuff_id'];
		$price = $value['price'];
		$created_at = $value['created_at'];
		if($memcache) {
			$memcache->set($user . ":" . $uid . ":silp", $price);
			$memcache->set($user . ":" . $uid . ":silsi", $stuff_id);  //ex. 4358354:4:silsi
			$memcache->set($user . ":" . $uid . ":silca", $created_at);
		}
		if($sell_item_indexing == "") {
			$sell_item_indexing = "$uid";
		}
		else {
			$sell_item_indexing .= ",$uid";
		}
	}
	if($memcache) {
		$memcache->set($user . ":sil", $sell_item_indexing);
	}
	return array($item_array, $sell_item_indexing);
}

function get_is_item_owned($user, $item_id) {
	global $DB;
	
	try {
		$r = $DB->GetOne('select how_many from stuff where user_id = ? and stuff_id = ?', array($user, $item_id));
	} catch (Exception $e) { return false; }
	
	if($r > 0) {
		return true;
	}
	else {
		return false;
	}	
}

function get_is_owned($user, $uid) {
	global $memcache, $DB;
	
	try {
		$sql = 'select id from items_sell where uid = ?';
		$result = $DB->GetOne($sql, array($uid));
	} catch (Exception $e) {
		return false;
	}
	
	if($result == false) {
		return false;
	}
	else {
		if($result == $user) {
			return true;
		}
		else {
			return false;
		}
	}
}

function echo_success($text) {
	echo "<div style='text-align:center'><fb:success><fb:message>$text</fb:message></fb:success></div>";
}

function echo_explanation($text) {
	echo "<div style='text-align:center'><fb:explanation><fb:message>$text</fb:message></fb:explanation></div>";
}

function echo_error($text) {
	echo "<div style='text-align:center'><fb:error><fb:message>$text</fb:message></fb:error></div>";
}

?>
