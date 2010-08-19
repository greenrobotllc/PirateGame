<?php
require_once 'includes.php';

$your_coins = get_coin_total($user);


$game_result = $_REQUEST['result'];
$gold = $_REQUEST['coins'];



if($game_result =='lose') {
	$msg="You attack and ....<br>You LOST :(  The $enemy pirates stole some of your booty!";
	$result="<h2>The enemy pirate stole $gold coins from you.  Arrr....there always be another day for fightin!</h2>" . image_return($you_lost_image) . "<h2><a href='clear_action.php'>Sail away!</a></h2>";

	
}

else if($game_result == 'tie') {
	$msg="You attack and ....<br>barely escaped alive!! Shiver me timbers! You weren't able to take any gold, but the enemy pirate didn't take any of yours either! Arrr..";
	$result='<h2>Arrr....there always be another day for fightin!</h2><br><h2><a href="clear_action.php">Sail away!</a></h2>';

}
else if(empty($game_result)) {
	$msg="You attack and ....<br>barely escaped alive!! Shiver me timbers! You weren't able to take any gold, but the enemy pirate didn't take any of yours either! Arrr..";
	$result='<h2>Arrr....there always be another day for fightin!</h2><br><h2><a href="clear_action.php">Sail away!</a></h2>';
}

else { //win!
	$msg="You attack and ....<br>You WON.  You stole some booty from those $enemy scallywags!";
	if($_REQUEST['stuff'] == 1) {
		$result="<h2>You found a treasure map on the merchant ship!<br>Use maps to search for treasure!<br>" . image_return($item_treasuremap_small) . "<br><br>You also took $gold coins from the enemy Pirate!<br>Arrrr...Good job matey</h2><br>" . image_return($pirate_coins_image) . "<br><h2><a href='clear_action.php'>Continue...</a></h2>";

	}
	else {
		$result="<h2>You took $gold coins from the enemy Pirate!<br>Arrrr...Good job matey</h2>" . image_return($pirate_coins_image) . "<br><h2><a href='clear_action.php'>Continue...</a></h2>";
	}
	


}

//update_action($user, "NULL");

print dashboard();

?>


<center>

<?php success_msg($msg); ?>
</center>	


<center>
	
	<div style="padding-bottom:10px">
		<?php echo $result; ?>
	</div>

</center>

<h1 style='text-align:center'>Battle History</h1>
<?php $h = $memcache->get($user . 'msbh'); ?>
<?php echo $h; ?>

<br>

<?php
echo adsense_468($user); ?>

