<?php

require_once 'includes.php';
global $DB;

$secondary = get_secondary_action($user);
if($secondary == 'monster_attack_result') {
  $facebook->redirect('monster_attack_result.php');
}

redirect_to_index_if_not_secondary($user, "attacked_by_monster");

$s = $_REQUEST['weird'];
//echo "s:";

//print_r($s);
//print_r($_REQUEST);

//exit("");

$enemy = $memcache->get($user . 'merchant_ship');
if($enemy == false) {
  $enemy = array('level' => 100, 'damage' => '0', 'team' => '', 'coin_total' => 100, 'cannons' => 100);
  $memcache->set($user . 'merchant_ship', $enemy);
}


$enemy_level = $enemy['level'];
$enemy_damage = $enemy['damage'];
//$enemy_team = $enemy['team'];
$enemy_cannons = $enemy['cannons'];
$enemy_coins = $enemy['coin_total'];
$enemy_health = $enemy_level - $enemy_damage;


if($enemy_coins < 1) {
	$enemy_coins = rand(1,100);
}




//your id
$your_id = $user;
$sql = 'select level, damage, team, coin_total from users where id = ?';
$battle_stats = $DB->GetRow($sql, array($user));
//print_r($battle_stats);
$your_level = $battle_stats['level'];
$your_damage = $battle_stats['damage'];
$your_team = $battle_stats['team'];
$your_coins = $battle_stats['coin_total'];

//get cannon level
$sql = 'select level from upgrades where user_id = ? and upgrade_name = ?';

$your_cannons = $DB->GetOne($sql, array($user, 'cannons'));
if(empty($your_cannons)) {
	$your_cannons = 1;
}


$round = get_round($user);

if(empty($round)) {
	$round = 1;
}
else {
	increment_round($user);
}

$monster_battle_history = $memcache->get($user . 'monster_battle_history');

$drink = $_REQUEST['rum'];

if($drink == 'drink') {
	$action_type='rum';
	$rum_required = round(get_crew_count($user) / 4);
	$rum_total = get_rum_count($user);
	
	if($rum_total > $rum_required) {
		drink_rum_battling($user, $rum_required);
		reset_round($user);
	   $history = "<h2 style='padding:5px; margin: 5px; border: thin dotted green; background-color: #eee'><span style='color:green'>+</span>Yer crew drank rum and are ready for battle!</h2>";
	}
	
}
else if($s == 'DYNAMITEPARROTS') {
	$dynamite_total = get_dynamite_count($user);
	$parrot_total = get_parrot_count($user);
	
	if($dynamite_total > 0 && $parrot_total > 0) {
	}
  $your_hit = rand(round($enemy_level * .05), round($enemy_level * .25));
  $enemy_hit = rand(round($enemy_level * .01), round($enemy_level * .05));
 
   $ra = rand(0,1);
   if($ra == 0) {
	   $history = "<h2 style='padding:5px; margin: 5px; border: thin dotted green; background-color: #eee'><span style='color:green'>+</span>Your parrot dropped some dynamite and hit the monster for $your_hit damage!</h2>";
	   //decrease dynamite count
	   set_dynamite_total($user, $dynamite_total - 1);
   $action_type = 'parrot';

   }
   else {
	   $history = "<h2 style='padding:5px; margin: 5px; border: thin dotted green; background-color: #eee'><span style='color:green'>+</span>Your parrot attacked but was killed in the process! It hit the monster for $your_hit damage.</h2>";   
	//decrease parrot count, decrease dynamite count
 	set_dynamite_total($user, $dynamite_total - 1);
	set_parrot_total($user, $parrot_total - 1);
   $action_type = 'parrotdied';
	
	 }
   
  
   $history .= "<h2 style='padding:5px; margin: 5px; border: thin dotted red; background-color: #eee'><span style='color:red'>-</span>The monster attacked you back for $enemy_hit damage!</h2>";


}
else if($s == 'EATHAM') {
	$success = eat_ham($user);
	
	$your_hit = 0;
	$history = "<h2 style='padding:5px; margin: 5px; border: thin dotted green; background-color: #eee'><span style='color:green'>+</span>You ate some ham and healed yerself 25%</h2>";
	
	$ra = rand(0,2);
	if($ra == 0) {
		$enemy_hit = 0;	
	}
	else if($ra == 1) {
	  $enemy_hit = rand(round($enemy_level * .01), round($enemy_level * .05));
	  $history .= "<h2 style='padding:5px; margin: 5px; border: thin dotted red; background-color: #eee'><span style='color:red'>-</span>The monster attacked you for $enemy_hit damage!</h2>";

	}
	else {
		$enemy_hit = $enemy_cannons;
	$history .= "<h2 style='padding:5px; margin: 5px; border: thin dotted red; background-color: #eee'><span style='color:red'>-</span>The monster attacked you for $enemy_hit damage!</h2>";

	}
	
	
	$action_type = 'ham';

	
}
else {


	$low = ceil($your_cannons / 2);
	$high = ceil($your_cannons);
	$your_hit = rand($low, $high);
	$low = ceil($enemy_cannons / 2);
	$high = ceil($enemy_cannons);
	$enemy_hit = rand($low, $high);
	

$history = "<h2 style='padding:5px; margin: 5px; border: thin dotted green; background-color: #eee'><span style='color:green'>+</span>You fired yer cannons for $your_hit damage!</h2>";
$history .= "<h2 style='padding:5px; margin: 5px; border: thin dotted red; background-color: #eee'><span style='color:red'>-</span>The monster attacked you back for $enemy_hit damage!</h2>";

$action_type = 'cannon';



}


$history .= $monster_battle_history;



set_damage($user, get_damage($user) + $enemy_hit);
//set_damage($enemy_id, get_damage($enemy_id) + $your_hit);
$enemy['damage'] =  $your_hit + $enemy_damage;
$memcache->set($user . 'merchant_ship', $enemy);


$your_health = get_level($user) - get_damage($user); // - $enemy_hit;
$enemy_health = $enemy_level - $enemy_damage - $your_hit;



if($your_health < 0) {
	$your_health=0;
}
if($enemy_health < 0) {
	$enemy_health=0;
}




$memcache->set($user . 'monster_battle_history', $history);

if($your_health < 1) {    
    reset_round($user);

	set_damage($user, get_level($user));
			
	update_secondary_action($user, "monster_attack_result");
	update_action($user, 'NULL');
	//you lost, lose treasure hunt
	$memcache->set($user . 'treasure_attack_mile', 0);


   //update your health to 50%
    $your_level = get_level($user);
    $half = round($your_level / 2);   
	$sql = 'update users set damage = damage - ? where id = ?';
	$DB->Execute($sql, array($half, $user));

    $memcache->set($user . 'merchant_ship', false);
	
	
	$bootylostname = '';
	$bootylostcount = '';
		
	$crew_lost = rand(0,2);
	kill_crewmembers($user, $crew_lost);
	
	if($crew_lost != 0) {
		$history .= "<h2 style='padding:5px; margin: 5px; border: thin dotted red; background-color: #eee'><span style='color:red'>-</span>The monster attacked and killed $crew_lost of yer crew!</h2>";
	  $memcache->set($user . 'monster_battle_history', $history);

	}
	
	$history = $memcache->get($user . 'monster_battle_history');

	$monster_type = $memcache->get($user . 'mt');
	if($monster_type == 'seamonster' || $monster_type == FALSE) {
		$monster_type = 'seamonster';
	}
	$memcache->set($user . 'mt', false);
	$DB->Execute('insert into npc_battles (user_id, type, level, cannons, result, coins, created_at, crew_lost) values(?, ?, ?, ?, ?, ?, now(), ?)', array($user, $monster_type, $enemy_level, $enemy_cannons, 'l', 0, $crew_lost));
	

	$facebook->redirect("$facebook_canvas_url/monster_attack_result.php?i=$battle_id&u=$your_hit&e=$enemy_hit&r=$round&d=$direction&f=$enemy_direction&coins=$enemy_coins&result=lose&crew=$crew_lost");

}
else if ($enemy_health < 1) {
	//you win
    reset_round($user);
    	
	log_coins($user, $enemy_coins, 'monster attack win', $enemy_id);

	$sql = 'update users set coin_total = coin_total + ? where id = ?';
	$DB->Execute($sql, array($enemy_coins, $user));
	
	$history .= "<h2 style='padding:5px; margin: 5px; border: thin dotted green; background-color: #eee'><span style='color:green'>+</span>The monster dropped $enemy_coins coins!!</h2>";


	update_secondary_action($user, "monster_attack_result");
    
    $memcache->set($user . 'merchant_ship', false);

	$memcache->set($user . 'treasure_attack_mile', 0);

	//$booty = 'sea monster scales';
	//$booty = 'sea monster head';
	//$booty = 'sea monster tail';
	
	$level = lower_level_up($user);
	if($level == true) {
	   $level_up = 1;
	   $history .= "<h2 style='padding:5px; margin: 5px; border: thin dotted green; background-color: #eee'><span style='color:green'>+</span>You leveled up!</h2>";
	   log_levels($user, 'level up from fighting monster');

	}
	else {
		$level_up = 0;
	}

	$memcache->set($user . 'monster_battle_history', $history);
	
	$DB->Execute('insert into npc_battles (user_id, type, level, cannons, result, coins, created_at, level_up) values(?, ?, ?, ?, ?, ?, now(), ?)', array($user, 'sea_monster', $enemy_level, $enemy_cannons, 'w', $enemy_coins, $level_up));
		
	$facebook->redirect("$facebook_canvas_url/monster_attack_result.php?u=$your_hit&e=$enemy_hit&r=$round&d=$direction&f=$enemy_direction&coins=$enemy_coins&result=win&level=$level");


}


else if(($round > 6)) {
//it's a tie, max rounds
    reset_round($user);
	
	update_secondary_action($user, "monster_attack_result");
    
	//you lost, lose treasure hunt
    update_action($user, 'NULL');
	
    $memcache->set($user . 'merchant_ship', false);

	$memcache->set($user . 'treasure_attack_mile', 0);
	
	$history = $memcache->get($user . 'monster_battle_history');
	$DB->Execute('insert into npc_battles (user_id, type, level, cannons, result, created_at) values(?, ?, ?, ?, ?, now())', array($user, 'sea_monster', $enemy_level, $enemy_cannons, 'tie'));
	
	$facebook->redirect("$facebook_canvas_url/monster_attack_result.php?u=$your_hit&e=$enemy_hit&r=$round&d=$direction&f=$enemy_direction&result=tie");
}

else {

	$facebook->redirect("$facebook_canvas_url/attacked_by_monster.php?u=$your_hit&e=$enemy_hit&r=$round&d=$direction&f=$enemy_direction&action_type=$action_type");

}


//if either person's hit power is 0 then:
//update the battle sql
//redirect to the win/lose page


?>
