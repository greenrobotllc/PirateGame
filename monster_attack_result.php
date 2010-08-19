<?php
require_once 'includes.php';

global $DB, $memcache, $you_lost_image, $pirate_coins_image;

redirect_to_index_if_not_secondary($user, 'monster_attack_result');
	
$your_coins = get_coin_total($user);

/* $game_id = $_REQUEST['i'];
if(empty($game_id)) {
	$facebook->redirect("$facebook_canvas_url/index.php");
} */

$sql = "select * from npc_battles where user_id = ? order by created_at desc";
$game = $DB->GetRow($sql, array($user));
//print_r($game);
$game_result = $game['result'];//$_REQUEST['result'];
$gold = $game['coins'];
$level_up = $game['level_up'];
$crew = $game['crew_lost'];


//print $crew;
//update_action($user, "NULL");

if($game_result =='w') {
	//update_action($user, 'treasure_map');
	
	$msg="The monster attacked and....<br>You WON!";
	
	$result="<h2>The monster dropped $gold coins!<br>Arrrr...Good job matey</h2>" . image_return($pirate_coins_image) . "<br>";
	
	if($level_up == 1) {
		$result .= "<h2>Level +1</h1>";
		
	}
	
	$result .= "<h2><a href='clear_secondary.php'>Continue on yer quest</a></h2>";
	
}

else if($game_result == 't') { 
	$msg="The monster attacked and yer crew defended the ship.... You finally managed to escape with yer lives.";	
	
	$result="<h2>You lost the path to the treasure.  Arrr....there always be another day for questin!</h2>" . image_return($you_lost_image) . "<h2><a href='clear_secondary.php'>Back to sailin'</a></h2>";

}
else { 
	//lose!
	if($crew !=0) {
		$msg="The monster attacked and ....<br>You LOST :(  The monster killed $crew of yer crew!!";
	}
	else if(!empty($bootylostcount)) {
		$msg="The monster attacked and ....<br>You LOST :(  $bootylostcount $bootylostname were lost in the fight!";
	}
	else {
		$msg="The monster attacked and ....<br>You LOST :( No crew was lost in the fight!";	
	}
	$result="<h2>You lost the path to the treasure.  Arrr....there always be another day for questin!</h2>" . image_return($you_lost_image) . "<h2><a href='clear_secondary.php'>Back to sailin'</a></h2>";

}


print dashboard();

?>


<center>
<?php success_msg($msg); ?>


	<div style="padding-bottom:10px">
		<?php echo $result; ?>
	
		<br>
		<?php
		
		//clear battle history
		$monster_battle_history = $memcache->get($user . 'monster_battle_history');
		echo "<h1 style='text-align:center'>Battle History</h1>";
		echo $monster_battle_history;
		?>
		
	</div>
	

<?php echo adsense_468($user); ?>	
</center>

