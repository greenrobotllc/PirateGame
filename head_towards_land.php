<?php

require_once 'includes.php';


//hack attack!
$current_action = get_current_action($user);
if($current_action != "land") {
  $facebook->redirect("$facebook_canvas_url/index.php");
}
$userMiles = get_miles_traveled($user);
$milesMax = get_max_miles($user);
if($userMiles >= $milesMax) {
	update_action($user, "NULL");
	$facebook->redirect('explore.php');
}

//is it an island or is it an enemy base?
//is it a gold on the island, pirate coins, a treasure map, just a beach

	$ra= rand(1,100);
	
	//echo "ra; $ra";
	
	if($ra < 45) { //enemy base
		$pvp_toggle = $memcache->get($user . 'pvp');
		if($pvp_toggle == 'off') {
		  update_action($user, "island");
		  $facebook->redirect("$facebook_canvas_url/island.php");		
		}
		else {
		  update_action($user, "enemy_base");
		  $facebook->redirect("$facebook_canvas_url/enemy_base.php");
		}
	}
	else { // island
		update_action($user, "island");
		$facebook->redirect("$facebook_canvas_url/island.php");
	}
	


?>
