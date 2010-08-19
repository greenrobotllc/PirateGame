<?php

require_once 'includes.php';
global $DB;

redirect_to_index_if_not($user, "island_treasure");

set_map_total($user, get_map_count($user) - 1);

$treasure_map_count = get_map_count($user);
if($treasure_map_count < 1) {
	update_action($user, 'NULL');
}

update_action($user, 'treasure_hunt');

$memcache->set($user . 'quested', 1, false, 60*5);

//set the current mile as last
$miles_traveled = get_miles_traveled($user);

//set miles
$miles = rand(10,15);
$memcache->set($user . 'treasure_hunt_miles', $miles);	       
$DB->Execute('update users set last_treasure_mile = ? where id = ?', array($miles_traveled, $user));

//maybe set an attack treasure_attack_mile
$ra = rand(0,0);
if($ra == 0) {
	//you will be attacked
	$ra2 = rand(5, $miles - 1);
	//$DB->Execute('update users set treasure_attack_mile =)
	$memcache->set($user . 'treasure_attack_mile', $ra2);	       

}
//clear battle history
$monster_battle_history = $memcache->set($user . 'monster_battle_history', false);


$facebook->redirect('index.php?msg=treasure-start');

?>