<?php

require_once 'includes.php';

global $DB;

if($action == 'merchant_ship_attack_result') {
  $facebook->redirect('merchant_ship_attack_result.php');
}
redirect_to_index_if_not_or($user, "found_merchant_ship", "attack_ship_merchant");

update_action($user, "attack_ship_merchant");


$enemy = $memcache->get($user . 'merchant_ship');
if($enemy == false) {
  $enemy = array('level' => 100, 'damage' => '0', 'team' => 'buccaneer', 'coin_total' => 100, 'cannons' => 100);
  $memcache->set($user . 'merchant_ship', $enemy);
}

$round = get_round($user);


$enemy_level = $enemy['level'];
$enemy_damage = $enemy['damage'];
$enemy_team = $enemy['team'];
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

$direction = $_REQUEST['d'];
$enemy_move = rand(1,3);
if($enemy_move == 1) { //if they're left and the random number says to go right, then actually go straight, and vice vs.

	if($direction == 'right') {
		$enemy_direction = 'straight';
	}
	else {
		$enemy_direction = 'left';
	}
	
}
else if($enemy_move == 2) {
	$enemy_direction = 'straight';
}
else {
	if($direction == 'left') {
		$enemy_direction = 'left';
	}
	else {
		$enemy_direction = 'right';
	}
}



$low = ceil($your_cannons * 1.75);
$high = ceil($your_cannons * 2.25);
$your_hit = rand($low, $high);

$low = ceil($enemy_cannons * 1.75);
$high = ceil($enemy_cannons * 2.25);
$enemy_hit = rand($low, $high);

//echo "enemy move: $enemy_move";
//echo "direction: $direction";

if($direction != 'straight' && $enemy_direction == $direction) {
	//chance for critical strike for left + right
	$critical = rand(1,5);
	if($critical == 3) {
		$your_hit = $your_hit * 2;
	}
	
} 
else if($direction == 'straight' && $enemy_direction == $direction) {
	//small chance for a critical hit
	// if straight
	$critical = rand(1,100);
	if($critical == 50) {
		$your_hit = $your_hit * 2;
	}

}
else if($direction == 'straight' && ($enemy_direction == 'left' || $enemy_direction == 'right')) { //partial hit
	//$your_hit = round($your_hit * .5);
	//$your_hit = 
}
else {
	//$your_hit = 0;
	//$enemy_hit = 0;
}

$merchant_ship_battle_history = $memcache->get($user . 'msbh');

$history = "<h2 style='padding:5px; margin: 5px; border: thin dotted green; background-color: #eee'><span style='color:green'>$round: </span>You fired yer cannons for $your_hit damage!</h2>";
$history .= "<h2 style='padding:5px; margin: 5px; border: thin dotted red; background-color: #eee'><span style='color:red'>$round: </span>The merchant ship attacked you back for $enemy_hit damage!</h2>";

$history .= $merchant_ship_battle_history;

$memcache->set($user . 'msbh', $history);

set_damage($user, get_damage($user) + $enemy_hit);
//set_damage($enemy_id, get_damage($enemy_id) + $your_hit);
$enemy['damage'] =  $your_hit + $enemy_damage;
$memcache->set($user . 'merchant_ship', $enemy);


//echo "your hit: $your_hit<br>";
//enemys hit
//$sql = 'select id from battles where user_id = ? and user_id_2 = ? and battle_type =?';
//$battle_id = $DB->GetOne($sql, array($user, $enemy_id, 's'));



//echo "enemy hit: $enemy_hit<br>";

$your_health = get_level($user) - get_damage($user); // - $enemy_hit;
$enemy_health = $enemy_level - $enemy_damage - $your_hit;



if($your_health < 0) {
	$your_health=0;
}
if($enemy_health < 0) {
	$enemy_health=0;
}
//set health in memcache
//set_health($user, $your_health);
//set_health($enemy_id, $enemy_health);


//echo "your health: $your_health<br>";
//echo "enemy health: $enemy_health<br>";


$round = get_round($user);

//$_REQUEST['r'];

if(empty($round)) {
	$round = 1;
}
else {
	//$round++;
	increment_round($user);
}

//$your_low_health_to_flee = rand(1,$your_level/5);
//$enemy_low_health_to_flee = rand(1,$enemy_level/5);


//if($your_health < 1 && $enemy_health) 
if($your_health < 1) {
	//you lose
    
    reset_round($user);

	$sql = 'update users set coin_total = 0 where id = ?';
	$DB->Execute($sql, array($user));
	//log coins lost for you
	log_coins($user, -$your_coins, 'merchant ship attack loss', $enemy_id);
	
	//$sql = 'update users set coin_total = coin_total + ? where id = ?';
	//$DB->Execute($sql, array($your_coins, $enemy_id));
	//log coins gained for enemy
	//log_coins($enemy_id, $your_coins, 'ship defense win', $user);
	
	
	//$sql = 'update battles set result = ?, gold_change = ? where user_id = ? and user_id_2 = ? and result=?';
	//$DB->Execute($sql, array('l', $your_coins, $user, $enemy_id, 'p'));

	// update damage for you
	//$sql = 'update users set damage = level where id = ?';
	//$DB->Execute($sql, array($user));
	set_damage($user, get_level($user));
	
	//update damage for enemy
	//$sql = 'update users set damage = damage + ? where id = ?';
	//$DB->Execute($sql, array($your_hit, $enemy_id));
	//set_damage($enemy_id, get_damage($enemy_id) + $your_hit);
	
	//$sql = 'update users set user_in_battle = 0 where id = ? OR id = ?';
	//$DB->Execute($sql, array($user, $enemy_id));
		
	update_action($user, "merchant_ship_attack_result");
	
   //update your health to 50%
    $your_level = get_level($user);
    $half = round($your_level / 2);   
	$sql = 'update users set damage = damage - ? where id = ?';
	$DB->Execute($sql, array($half, $user));

    $memcache->set($user . 'merchant_ship', false);
    
    if($your_coins != 0) {
    	$history .= "<h2 style='padding:5px; margin: 5px; border: thin dotted red; background-color: #eee'><span style='color:red'>-</span>You lost the battle! The enemy ship stole $your_coins coins from you!!</h2>";
	}
	else {
    	$history .= "<h2 style='padding:5px; margin: 5px; border: thin dotted red; background-color: #eee'><span style='color:red'>-</span>You lost the battle! Arrr....</h2>";	
	}
	$memcache->set($user . 'msbh', $history);
	
	$facebook->redirect("$facebook_canvas_url/merchant_ship_attack_result.php?i=$battle_id&u=$your_hit&e=$enemy_hit&r=$round&d=$direction&f=$enemy_direction&coins=$your_coins&result=lose");

}
else if ($enemy_health < 1) {
	//you win
    reset_round($user);
	//$sql = 'update users set coin_total = 0 where id = ?';
	//$DB->Execute($sql, array($enemy_id));
	
	log_coins($user, $enemy_coins, 'merchant ship attack win', $enemy_id);

	$sql = 'update users set coin_total = coin_total + ? where id = ?';
	$DB->Execute($sql, array($enemy_coins, $user));
	

	//give them a treasure map 1/3 of the time
	$ra = rand(1,100);
	if($ra < 33) {
		$stuff_id = 1;
		$give_stuff = true;
	}
	else {
		$give_stuff = false;
	}

	if($give_stuff) {
		$sql = 'insert into stuff (user_id, stuff_id, how_many, updated_at) values(?, ?, 1, now()) on duplicate key update how_many = how_many + 1';
		$DB->Execute($sql, array($user, $stuff_id));
	}



	update_action($user, "merchant_ship_attack_result");
    
    $memcache->set($user . 'merchant_ship', false);
	$history .= "<h2 style='padding:5px; margin: 5px; border: thin dotted green; background-color: #eee'><span style='color:green'>+: </span>You won the battle! You stole $enemy_coins coins from the merchant ship!!</h2>";
	 
	$memcache->set($user . 'msbh', $history);

	$facebook->redirect("$facebook_canvas_url/merchant_ship_attack_result.php?u=$your_hit&e=$enemy_hit&r=$round&d=$direction&f=$enemy_direction&coins=$enemy_coins&result=win&stuff=$stuff_id");


}


else if(($round > 5)) {
//it's a tie, max rounds
    reset_round($user);

	//$sql = 'update battles set result = ? where user_id = ? and user_id_2 = ? and result=?';
	//$DB->Execute($sql, array('t', $user, $enemy_id, 'p'));

	//$sql = 'update users set user_in_battle = 0 where id = ? OR id = ?';
	//$DB->Execute($sql, array($user, $enemy_id));

	update_action($user, "merchant_ship_attack_result");
    
    $memcache->set($user . 'merchant_ship', false);

	$facebook->redirect("$facebook_canvas_url/merchant_ship_attack_result.php?u=$your_hit&e=$enemy_hit&r=$round&d=$direction&f=$enemy_direction&result=tie");
}

else {


	$facebook->redirect("$facebook_canvas_url/attack_ship_merchant.php?u=$your_hit&e=$enemy_hit&r=$round&d=$direction&f=$enemy_direction");

}


//if either person's hit power is 0 then:
//update the battle sql
//redirect to the win/lose page


?>
