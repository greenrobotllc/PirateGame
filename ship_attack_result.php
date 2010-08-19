<?php
require_once 'includes.php';

global $DB, $memcache;


//redirect_to_index_if_not($user, "enemy_base");

//echo get_current_action($user);

$type= get_team($user);


$your_coins = get_coin_total($user);

$game_id = $_REQUEST['i'];
if(empty($game_id)) {

	$game_id = $DB->GetOne('select id from battles where user_id = ? order by created_at desc', array($user));
	
	//$facebook->redirect("$facebook_canvas_url/index.php");
}


$sql = "select * from battles where id = ?";
$game = $DB->GetRow($sql, $game_id);
//print_r($game);
$game_result = $game['result'];
$gold = $game['gold_change'];
$enemy = $game['opponent_type'];
$enemy_id = $game['user_id_2'];

//update_action($user, "NULL");

if($game_result =='l') {
	$msg="You attack and ....<br>You LOST :(  The $enemy pirates stole some of your booty!";
	$result="<h2>The $enemy pirate " . get_first_name_for_id($enemy_id) . " stole $gold coins from you.  <br><br>Yer health was healed by 50% after 
losing!<br><br>Arrr....there always be another day for fightin!</h2>" . image_return($you_lost_image) . "<h2><a href='clear_action.php'>Sail away!</a></h2>";

	
}

else if($game_result == 't') {
	$msg="You attack and ....<br>barely escaped alive!! Shiver me timbers! You weren't able to take any gold, but <fb:name uid='$enemy_id' firstnameonly='true'/> didn't take any of yours either! Arrr..";
	$result='<h2>Arrr....there always be another day for fightin!</h2><br><h2><a href="clear_action.php">Sail away!</a></h2>';

}

else { //win!
	$msg="You attack and ....<br>You WON.  You stole some booty from those $enemy scallywags!";
	$result="<h2>You took $gold coins from the $enemy Pirate " . get_first_name_for_id($enemy_id) . " Arrrr...Good job matey</h2>" . image_return($pirate_coins_image) . "<br><h2><a href='clear_action.php'>Continue...</a></h2>";

}

$DB->Execute('update users set battling_enemy_id = 0 where id = ?', array($user));

//update_action($user, "NULL");

print dashboard();

?>


<center>

<?php success_msg($msg); ?>

	
	<div style="padding-bottom:10px">
		<?php echo $result; ?>
	</div>

</center>

<br>

<?php

$history = $memcache->get($user . 'pvp_attack_history');

echo "<center><h1>Battle History</h1></center>";
echo $history;
echo "<center><br><h2><a href='clear_action.php'>Sail away!</a></h2><br></center>";

?>

<br>

<?php echo adsense_468($user); ?>
