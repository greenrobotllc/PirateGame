<?php

require_once 'includes.php';

global $DB, $memcache;

$action = get_current_action($user);
if($action == 'ship_attack_result') {
  $facebook->redirect('ship_attack_result.php');
}

redirect_to_index_if_not_or($user, "ship", "attack_ship");

$enemy_id = $DB->GetOne('select battling_enemy_id from users where id = ?', array($user));

if($enemy_id == FALSE || $enemy_id == 0) {
    update_action($user, 'NULL');
    set_was_attacked($user, 0);
    set_was_bombed($user, 0);
    $DB->Execute('delete from battles where user_id = ? and result = ?', array($user, 'p'));
    $facebook->redirect('index.php?msg=ship-ran-away');
}


$sql = 'select id, level, damage, team, coin_total from users where id = ?';
$enemy = $DB->GetRow($sql, array($enemy_id));

$enemy_id = $enemy['id'];
$enemy_level = $enemy['level'];
$enemy_damage = $enemy['damage'];
$enemy_team = $enemy['team'];
$enemy_coins = $enemy['coin_total'];

if($enemy_coins < 1) {
	$enemy_coins = rand(1,100);
}

$sql = 'select level from upgrades where user_id = ? and upgrade_name = ?';
$enemy_cannons = $DB->GetOne($sql, array($enemy_id, 'cannons'));
if(empty($enemy_cannons) || $enemy_cannons == 0) {
	$enemy_cannons = 1;
}

$enemy_hull = $DB->GetOne($sql, array($enemy_id, 'hull'));
if(empty($enemy_hull) || $enemy_hull == 0) {
	$enemy_hull = 0;
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
if(empty($your_cannons) || $your_cannons == 0) {
	$your_cannons = 1;
}

$your_hull = $DB->GetOne($sql, array($user, 'hull'));
if(empty($your_hull) || $your_hull == 0) {
	$your_hull = 0;
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


$pvp_attack_history = $memcache->get($user . 'pvp_attack_history');

$defender_pvp_attack_history = $memcache->get($enemy_id . 'defender_pvp_attack_history');


$low = ceil($your_cannons * 1.75);
$high = ceil($your_cannons * 2.25);
$your_hit = rand($low, $high);
//reduce your_hit by half of your hull level
$your_hit = $your_hit - round($enemy_hull/rand(1.75, 2.25));
if($your_hit < 1) {
	$your_hit = 1;
}

$low = ceil($enemy_cannons * 1.75);
$high = ceil($enemy_cannons * 2.25);
$enemy_hit = rand($low, $high);
//reduce enemy_hit by half of hull level
$enemy_hit = $enemy_hit - round($your_hull/rand(1.75, 2.25));
if($enemy_hit < 1) {
	$enemy_hit = 1;
}


//echo "enemy move: $enemy_move";
//echo "direction: $direction";

if($direction != 'straight' && $enemy_direction == $direction) {
	//chance for critical strike for left + right
	$critical = rand(1,10);
	if($critical == 3) {
		$your_hit = $your_hit * 2;
	}
	
} 
else if($direction == 'straight' && $enemy_direction == $direction) {
	//small chance for a critical hit
	// if straight
	$critical = rand(1,10);
	if($critical == 50) {
		$your_hit = round($your_hit * 2);
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

//set_damage($user, $enemy_hit);
//set_damage($enemy_id, $your_hit);
if(empty($enemy_id) || !isset($enemy_id)) {
	update_action($user, "attack_ship_merchant");
	$facebook->redirect('attack_ship_merchant.php');
}

set_damage($user, get_damage($user) + $enemy_hit);
set_damage($enemy_id, get_damage($enemy_id) + $your_hit);
	


//echo "your hit: $your_hit<br>";

//enemys hit

$sql = 'select id from battles where user_id = ? and user_id_2 = ? and battle_type =? order by id desc';

$battle_id = $DB->GetOne($sql, array($user, $enemy_id, 's'));



//echo "enemy hit: $enemy_hit<br>";

$your_health = get_level($user) - get_damage($user); // - $enemy_hit;
$enemy_health = get_level($enemy_id) - get_damage($enemy_id); // - $your_hit;



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




if($your_health < 1 && $enemy_health > 0) {    
	//you lose
    
    reset_round($user);

	$sql = 'update users set coin_total = 0 where id = ?';
	$DB->Execute($sql, array($user));
	//log coins lost for you
	log_coins($user, -$your_coins, 'ship attack loss', $enemy_id);
	
	$sql = 'update users set coin_total = coin_total + ? where id = ?';
	$DB->Execute($sql, array($your_coins, $enemy_id));
	//log coins gained for enemy
	log_coins($enemy_id, $your_coins, 'ship defense win', $user);
	
	
	$sql = 'update battles set result = ?, gold_change = ? where user_id = ? and user_id_2 = ? and result=?';
	$DB->Execute($sql, array('l', $your_coins, $user, $enemy_id, 'p'));

	// update damage for you
	//$sql = 'update users set damage = level where id = ?';
	//$DB->Execute($sql, array($user));
	set_damage($user, get_level($user));
	
	//update damage for enemy
	//$sql = 'update users set damage = damage + ? where id = ?';
	//$DB->Execute($sql, array($your_hit, $enemy_id));
	set_damage($enemy_id, get_damage($enemy_id) + $your_hit);
	
	$sql = 'update users set user_in_battle = 0 where id = ? OR id = ?';
	$DB->Execute($sql, array($user, $enemy_id));
		
	update_action($user, "ship_attack_result");
	
   //update your health to 50%
    $your_level = get_level($user);
    $half = round($your_level / 2);   
	$sql = 'update users set damage = damage - ?, battling_enemy_id = 0 where id = ?';
	$DB->Execute($sql, array($half, $user));


	//battle history for the final killing blow for both defender and attacker




	if($your_coins == 0) {
		$history .= "<h2 style='padding:5px; margin: 5px; border: thin dotted green; background-color: #eee'><span style='color:red'>-</span>Yer hitpoints fell to 0. The enemy has $enemy_health hitpoints.  You lost the battle!</h2>";

		$defender_history .= "<h2 style='padding:5px; margin: 5px; border: thin dotted green; background-color: #eee'><span style='color:green'>+</span>The enemies hitpoints fell to 0! You won the battle!</h2>";
	

	}
	else {
		$history .= "<h2 style='padding:5px; margin: 5px; border: thin dotted green; background-color: #eee'><span style='color:red'>-</span>Yer hitpoints fell to 0. The enemy has $enemy_health hitpoints.  You lost the battle and $your_coins coins!</h2>";

		$defender_history .= "<h2 style='padding:5px; margin: 5px; border: thin dotted green; background-color: #eee'><span style='color:green'>+</span>The enemies hitpoints fell to 0! You won the battle and $your_coins coins!</h2>";
	
	}

	$history .= "<h2 style='padding:5px; margin: 5px; border: thin dotted green; background-color: #eee'><span style='color:green'>$round: </span>You fired yer cannons for $your_hit damage! The enemy has $enemy_health health left.</h2>";

	$history .= "<h2 style='padding:5px; margin: 5px; border: thin dotted red; background-color: #eee'><span style='color:red'>$round: </span>The enemy pirate attacked you back for $enemy_hit damage! You have $your_health health left.</h2>";

	$defender_history .= "<h2 style='padding:5px; margin: 5px; border: thin dotted green; background-color: #eee'><span style='color:green'>$round: </span>You fired yer cannons back for $enemy_hit damage! The enemy has $your_health health left.</h2>";

	$defender_history .= "<h2 style='padding:5px; margin: 5px; border: thin dotted red; background-color: #eee'><span style='color:red'>$round: </span>The enemy pirate attacked you for $your_hit damage! You have $enemy_health health left.</h2>";


	$history .= $pvp_attack_history;
	$memcache->set($user . 'pvp_attack_history', $history);
	
	$defender_history .= $defender_pvp_attack_history;
	$memcache->set($enemy_id . 'defender_pvp_attack_history', $defender_history);

		
	$facebook->redirect("$facebook_canvas_url/ship_attack_result.php?i=$battle_id&u=$your_hit&e=$enemy_hit&r=$round&d=$direction&f=$enemy_direction");

}
else if ($enemy_health < 1 && $your_health > 0) {
    //there is a weird bug where sometimes multiple attacks are showing up in the logs for the same
    //user_id vs user_id_2 attack  this shouldn't happen because the action is set to null but
    //hopefully this fixes it anyway.... =/
    //it sets a flag in memcache lasting 60 seconds to true, and if true that prevents this from running again
    $flag = $memcache->get($user . 'attacked' . $enemy_id);
    
    if($flag == 1) {
        update_action($user, 'NULL');
        set_was_attacked($user, 0);
        set_was_bombed($user, 0);
        $DB->Execute('delete from battles where user_id = ? and result = ?', array($user, 'p'));
        $facebook->redirect('index.php?msg=ship-ran-away'); 
    }
    
    $memcache->set($user . 'attacked' . $enemy_id, 1, false, 60);
    
	//you win
    reset_round($user);
	$sql = 'update users set coin_total = 0 where id = ?';
	$DB->Execute($sql, array($enemy_id));
	

	$sql = 'update users set coin_total = coin_total + ? where id = ?';
	
	if($enemy_coins < 100) {
	   $enemy_coins = 100 + rand(1, 150);
	}

	log_coins($user, $enemy_coins, 'ship attack win', $enemy_id);

	$DB->Execute($sql, array($enemy_coins, $user));
	
	log_coins($enemy_id, -$enemy_coins, 'ship defense loss', $user);

	$sql = 'update battles set result = ?, gold_change = ? where user_id = ? and user_id_2 = ? and result=?';
	$DB->Execute($sql, array('w', $enemy_coins, $user, $enemy_id, 'p'));	

	// update damage for you
	//DON"T TAKE AWAY DAMAGE IF ENEMY IS ALREADY DEAD
	//set_damage($user, get_damage($user) + $enemy_hit);
	
	//$sql = 'update users set damage = level where id = ?';
	//$DB->Execute($sql, array($enemy_id));
	set_damage($enemy_id, get_level($enemy_id));
	
	$sql = 'update users set user_in_battle = 0 where id = ? OR id = ?';
	$DB->Execute($sql, array($user, $enemy_id));


    //update enemy health to 50%
    $enemy_level = get_level($enemy_id);
    $half = round($enemy_level / 2);   
	$sql = 'update users set damage = damage - ? where id = ?';
	$DB->Execute($sql, array($half, $enemy_id));


	$sql = 'update users set battling_enemy_id = 0 where id = ?';
	$DB->Execute($sql, array($user));

	update_action($user, "ship_attack_result");




	$history .= "<h2 style='padding:5px; margin: 5px; border: thin dotted green; background-color: #eee'><span style='color:green'>+</span>You won the battle and took $enemy_coins coins from the enemy pirate!</h2>";

	$history .= "<h2 style='padding:5px; margin: 5px; border: thin dotted green; background-color: #eee'><span style='color:green'>$round: </span>You fired yer cannons for $your_hit damage! The enemy has $enemy_health health left.</h2>";

	$history .= "<h2 style='padding:5px; margin: 5px; border: thin dotted red; background-color: #eee'><span style='color:red'>$round: </span>The enemy pirate attacked you back for $enemy_hit damage! You have $your_health health left.</h2>";


	$defender_history .= "<h2 style='padding:5px; margin: 5px; border: thin dotted red; background-color: #eee'><span style='color:red'>-</span>You lost the battle! The enemy pirate took $enemy_coins coins from you!</h2>";


	$defender_history .= "<h2 style='padding:5px; margin: 5px; border: thin dotted green; background-color: #eee'><span style='color:green'>$round: </span>You fired yer cannons back for $enemy_hit damage! The enemy has $your_health health left.</h2>";

	$defender_history .= "<h2 style='padding:5px; margin: 5px; border: thin dotted red; background-color: #eee'><span style='color:red'>$round: </span>The enemy pirate attacked you for $your_hit damage! You have $enemy_health health left.</h2>";
	

	$history .= $pvp_attack_history;
	$memcache->set($user . 'pvp_attack_history', $history);


	$defender_history .= $defender_pvp_attack_history;
	$memcache->set($enemy_id . 'defender_pvp_attack_history', $defender_history);
	
	$facebook->redirect("$facebook_canvas_url/ship_attack_result.php?i=$battle_id&u=$your_hit&e=$enemy_hit&r=$round&d=$direction&f=$enemy_direction");


}

else if(($round > 4) || ($enemy_health < 1 && $your_health < 1) ) {
//it's a tie, max rounds
    	$sql = 'update users set battling_enemy_id = 0 where id = ?';
	$DB->Execute($sql, array($user));

	reset_round($user);

	$sql = 'update battles set result = ? where user_id = ? and user_id_2 = ? and result=?';
	$DB->Execute($sql, array('t', $user, $enemy_id, 'p'));

	$sql = 'update users set user_in_battle = 0 where id = ? OR id = ?';
	$DB->Execute($sql, array($user, $enemy_id));

	update_action($user, "ship_attack_result");
	
	
	//tie history
	$history .= "<h2 style='padding:5px; margin: 5px; border: thin dotted gray; background-color: #eee'><span style='color:gray'>+</span>The battle was a tie!</h2>";

	$history .= "<h2 style='padding:5px; margin: 5px; border: thin dotted green; background-color: #eee'><span style='color:green'>$round: </span>You fired yer cannons for $your_hit damage! The enemy has $enemy_health health left.</h2>";

	$history .= "<h2 style='padding:5px; margin: 5px; border: thin dotted red; background-color: #eee'><span style='color:red'>$round: </span>The enemy pirate attacked you back for $enemy_hit damage! You have $your_health health left.</h2>";

	$defender_history .= "<h2 style='padding:5px; margin: 5px; border: thin dotted gray; background-color: #eee'><span style='color:gray'>+</span>The battle was a tie!</h2>";

	$defender_history .= "<h2 style='padding:5px; margin: 5px; border: thin dotted green; background-color: #eee'><span style='color:green'>$round: </span>You fired yer cannons back for $enemy_hit damage! The enemy has $your_health health left.</h2>";

	$defender_history .= "<h2 style='padding:5px; margin: 5px; border: thin dotted red; background-color: #eee'><span style='color:red'>$round: </span>The enemy pirate attacked you for $your_hit damage! You have $enemy_health health left.</h2>";

	$history .= $pvp_attack_history;
	$memcache->set($user . 'pvp_attack_history', $history);
	
	$defender_history .= $defender_pvp_attack_history;
	$memcache->set($enemy_id . 'defender_pvp_attack_history', $defender_history);
	
	
	

	$facebook->redirect("$facebook_canvas_url/ship_attack_result.php?i=$battle_id&u=$your_hit&e=$enemy_hit&r=$round&d=$direction&f=$enemy_direction");
}

else {
	// update health points in db for both users
	//$sql = 'update users set damage = damage + ? where id = ?';
	//$DB->Execute($sql, array($enemy_hit, $user));
	//set_damage($user, get_damage($user) + $enemy_hit);
	//set_damage($enemy_id, get_damage($enemy_id) + $your_hit);
	
	//$sql = 'update users set damage = damage + ? where id = ?';
	//$DB->Execute($sql, array($your_hit, $enemy_id));
	
	//else redirect back to attack_ship with a mesage of the hits taken,
	
	$history .= "<h2 style='padding:5px; margin: 5px; border: thin dotted green; background-color: #eee'><span style='color:green'>$round: </span>You fired yer cannons for $your_hit damage! The enemy has $enemy_health health left.</h2>";

$history .= "<h2 style='padding:5px; margin: 5px; border: thin dotted red; background-color: #eee'><span style='color:red'>$round: </span>The enemy pirate attacked you back for $enemy_hit damage! You have $your_health health left.</h2>";
	$history .= $pvp_attack_history;
	$memcache->set($user . 'pvp_attack_history', $history);
	


	$defender_history .= "<h2 style='padding:5px; margin: 5px; border: thin dotted green; background-color: #eee'><span style='color:green'>$round: </span>You fired yer cannons for $enemy_hit damage! The enemy has $your_health health left.</h2>";

$defender_history .= "<h2 style='padding:5px; margin: 5px; border: thin dotted red; background-color: #eee'><span style='color:red'>$round: </span>The enemy pirate attacked you for $your_hit damage! You have $enemy_health health left.</h2>";
	$defender_history .= $defender_pvp_attack_history;
	$memcache->set($enemy_id . 'defender_pvp_attack_history', $defender_history);
	


	$facebook->redirect("$facebook_canvas_url/attack_ship.php?u=$your_hit&e=$enemy_hit&r=$round&d=$direction&f=$enemy_direction");

}


//if either person's hit power is 0 then:
//update the battle sql
//redirect to the win/lose page


?>
