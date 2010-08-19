
<?php
ob_start();
require_once 'includes.php';

?>



<?php

$hit = $_REQUEST['h'];
$direction = $_REQUEST['d'];
$your_hit = $_REQUEST['u'];
$enemy_hit = $_REQUEST['e'];
$enemy_direction = $_REQUEST['f'];

redirect_to_index_if_not_or($user, "ship", "attack_ship");

//echo get_current_action($user);

update_action($user, "attack_ship");
$round = get_round($user);
?>



<script>
<!--
  
  function attackship(e, direction) {
	//e.getForm().setDisabled(true); 
	document.getElementById('attackstraight').setDisabled(true);
	document.getElementById('attackleft').setDisabled(true);
	document.getElementById('attackright').setDisabled(true);

	e.setStyle("background", "gray"); 
	document.setLocation("<?php echo $facebook_canvas_url; ?>/attack_ship_action.php?d=" + direction);

  }
// --!>
</script>
<?php

print dashboard();

?>





<?php 

$sql = 'select level, damage, team, coin_total from users where id = ?';
$battle_stats = $DB->GetRow($sql, array($user));
//print_r($battle_stats);
$your_level = $battle_stats['level'];
$your_damage = $battle_stats['damage'];
$your_team = $battle_stats['team'];
$your_coins = $battle_stats['coin_total'];
$your_health = $your_level - $your_damage;

//get cannon level
$sql = 'select level from upgrades where user_id = ? and upgrade_name = ?';

$your_cannons = $DB->GetOne($sql, array($user, 'cannons'));
if(empty($your_cannons)) {
	$your_cannons = 0;
}


$your_hull = $DB->GetOne($sql, array($user, 'hull'));
if(empty($your_hull)) {
	$your_hull = 0;
}

$sql = 'select battling_enemy_id from users where id = ?';
$found_enemy_id = $DB->GetOne($sql, array($user));

if($found_enemy_id ) {
	$enemy_id = $found_enemy_id; //already in a battle...
	$sql = 'select id, level, damage, team, coin_total from users where id = ?';
  	$msg = "Shiver me timbers! The attack is on!";

	
	if(isset($_REQUEST['u']) && $_REQUEST['u'] == 0) {
		$msg = 'Arrr... You fire and miss!!';
	}
	
	$enemy = $DB->GetRow($sql, array($enemy_id));
}


//print_r($found_enemy_id);
else { //find a new enemy!


	//do the new way 50 of the time, old way 50% of the time
	$ra = rand(1,2);
	
	if($ra ==1) {
		$enemy = new_ship_battle_smart($user);
		
		if($enemy == $user) {
			$enemy = new_ship_battle($user);
		}
	}
	else {
		$enemy = new_ship_battle($user);
		if($enemy == $user) {
			$enemy = FALSE;
		}		
	}
	//echo "enemy is: $enemy";
	if($enemy == FALSE) {
		update_action($user, "attack_ship_merchant");
		$facebook->redirect("attack_ship_merchant.php");
	}
    
    $sql = 'update users set battling_enemy_id = ? where id = ?';
    $enemy_id = $enemy['id'];
	$DB->Execute($sql, array($enemy_id, $user));
	
	$msg = "You're fightin!";
	
	//clear the battle history for the attacker
	$memcache->set($user . 'pvp_attack_history', false);

	//clear for defender
	$memcache->set($enemy_id . 'defender_pvp_attack_history', false);

	
}

//print_r($enemy);

$enemy_id = $enemy['id'];

if(empty($enemy_id) || $enemy_id == $user) {
	update_action($user, "attack_ship_merchant");
	$facebook->redirect("attack_ship_merchant.php");
}

$enemy_level = $enemy['level'];
$enemy_damage = $enemy['damage'];
$enemy_team = $enemy['team'];
$enemy_coins = $enemy['coin_total'];

$enemy_health = $enemy_level - $enemy_damage;

//get cannon level for the enemy
$sql = 'select level from upgrades where user_id = ? and upgrade_name = ?';
$enemy_cannons = $DB->GetOne($sql, array($enemy_id, 'cannons'));
if(empty($enemy_cannons)) {
	$enemy_cannons = 0;
}

$enemy_hull = $DB->GetOne($sql, array($enemy_id, 'hull'));
if(empty($enemy_hull)) {
	$enemy_hull = 0;
}

set_was_attacked($enemy_id, $user);

if($hit == 'miss') { //it was a miss
	$msg = "Arrrrrrr... you fired $direction and missed! and caused <b>$your_hit damage</b>.  They fired back, causing you <b>$enemy_hit damage</b>";
	
}
else if(!empty($your_hit) && !empty($enemy_hit)) {
	$msg = "Arrrrrrr... you fired and caused <b>$your_hit damage</b>.  They fired back, causing you <b>$enemy_hit damage</b>";
}
else {
	//$msg = 'Shiver Me Timbers! the Attack is ON';
}

?>


<center>

<?php success_msg($msg); ?><center>

<table style='text-align:center; padding:10px; margin:10px; border: 1px dotted black' cellspacing=5 cellpadding=5 border=0>
<tr>

<td align='center' style='border: 1px dotted black; background-color: #3B5998; color: #FFFFFF'>


<span style='text-align:center'>
	<?php echo get_square_profile_pic($user); ?>

</span>

<a href='user_profile.php?user=<?php echo $user; ?>' style='color:#FFFFFF'>Cap'n <?php echo get_first_name_for_id($user); ?></a>

<p>Level <?php echo $your_type; ?> <?php echo $your_level; ?>  <?php echo ucwords($your_team); ?> Pirate (<?php echo $your_health; ?> health remaining)</p>
<p>Level <?php echo $your_cannons; ?> Cannons</p>

<p>Level <?php echo $your_hull; ?> Hull</p>

<?php if($your_coins == 1): ?>
	<p><?php echo $your_coins; ?> coin onboard</p>
<?php else: ?>
	<p><?php echo $your_coins; ?> coins onboard</p>

<?php endif; ?>

</td>

		<td>
		<?php image($cannon_image); ?>


		</td>



<td align='center' style='border: 1px dotted black; background-color: #3B5998; color: #FFFFFF'>

<span style='text-align:center'>
<?php echo get_square_profile_pic($enemy_id); ?>
</span>

<a href='user_profile.php?user=<?php echo $enemy_id; ?>' style='color:#FFFFFF'>Cap'n <?php echo get_first_name_for_id($enemy_id); ?></a>


<p>Level <?php echo $enemy_level; ?>  <?php echo ucwords($enemy_team); ?> Pirate (<?php echo $enemy_health; ?>  health remaining)</p>
<p>Level <?php echo $enemy_cannons; ?> Cannons</p>

<p>Level <?php echo $enemy_hull; ?> Hull</p>

<?php if($enemy_coins == 1): ?>
	<p><?php echo $enemy_coins; ?> coin onboard</p>
<?php elseif ($enemy_coins > 1): ?>
	<p><?php echo $enemy_coins; ?> coins onboard</p>
<?php endif; ?>
</td>

</tr>

<tr>

<td>


<?php if($enemy_direction == 'left'): ?>

<?php image($ship_corsair_175_image); ?>
<br>
<div style='text-align:center'><strong>Last known position of <?php echo ucwords($enemy_team); ?> Ship</strong></div>



<?php endif; ?>


</td>

<td>

<?php if($enemy_direction == 'straight'): ?>

<?php image($ship_corsair_175_image); ?>
<br>
<div style='text-align:center'><strong>Last known position of <?php echo ucwords($enemy_team); ?> Ship</strong></div>



<?php endif; ?>

</td>

<td align='center'>

<?php if($enemy_direction == 'right'): ?>
<?php image($ship_corsair_175_image); ?>
<br>
<div style='text-align:center'><strong>Last known position of <?php echo ucwords($enemy_team); ?> Ship</strong></div>

<?php endif; ?>
</td>


</tr>
<tr>
<?php 

$next_round = get_round($user) + 1;

if(empty($next_round)) {
	$next_round = 1;
}
//else {
//	$next_round++;
//}

?>

<td>


	<center>
			<form action="attack_ship_action.php">
			<input type="hidden" value="left" name="d"/>

			<input id='attackleft'  onclick = 'attackship(this, "left"); return false;' style="width:150px; height:65px; font-size: 150%" class="inputsubmit" type="submit" value="FIRE LEFT!!" name="submit"/>
			</form>
		</center>
		
			</td>
			
			<td>
			
			<form action="attack_ship_action.php">
			<input type="hidden" value="straight" name="d"/>

			<input id='attackstraight' onclick = 'attackship(this, "straight"); return false;' style="width:150px; height:65px; font-size: 150%" class="inputsubmit" type="submit" value="FIRE STRAIGHT!!" name="submit"/>
			</form>
			
			</td>
			
			
			<td>
			<center>
			
			<form action="attack_ship_action.php">
			<input type="hidden" value="right" name="d"/>

			<input id='attackright'  onclick = 'attackship(this, "left"); return false;' style="width:150px; height:65px; font-size: 150%" class="inputsubmit" type="submit" value="FIRE RIGHT!!" name="submit"/>
			</form>
			</center>
			</td>
</tr>

<tr><td colspan=4 align='center' style='font-size:120%'>


<?php

$ham_count = get_ham_count($user);

if($ham_count > 0) {

?>
<center>
*Heal yourself by <a href='item_action.php?item=ham'>eating ham</a> (<?php echo $ham_count; ?> left)</center>

<?

}
else {
?>

<center>*Heal yourself by <a href='buy.php?u=ham'>buying some ham</a> (50 coins)</center>

<?php

}
?>






</td></tr>

</table>


			
		
</center>

<?php //  onclick = 'this.setDisabled(true);  this.setStyle("background", "gray"); this.submit(); return true;'  ?>

<h2 style="text-align:center; padding-bottom: 10px"><a href="run_away_from_ship.php">Run away!!</a></h2>





</center>	
<?php
 $h = $memcache->get($user . 'pvp_attack_history');
 ?>
 

<?php if(!empty($h)) 
{

?>

<h1 style='text-align:center'>Battle History</h1>

<?php
echo $h;

}
echo '<br>';

echo adsense_468($user);
echo '<br>';
//require_once "my_pirate.inc.php";

//require_once "world_stats.inc.php";
//set_profile($user);





?>


<?php require_once 'footer.php'; ?>
