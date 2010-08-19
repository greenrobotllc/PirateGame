<?php

require_once 'includes.php';
global $DB, $memcache, $cannon_image, $merchant_image;

?>


<?php

$hit = $_REQUEST['h'];
$direction = $_REQUEST['d'];
$your_hit = $_REQUEST['u'];
$enemy_hit = $_REQUEST['e'];
$enemy_direction = $_REQUEST['f'];

//take this out later
//update_action($user, "NULL");
//$facebook->redirect("$facebook_canvas_url/index.php");
redirect_to_index_if_not_or($user, "found_merchant_ship", "attack_ship_merchant");

//echo get_current_action($user);

update_action($user, "attack_ship_merchant");
$round = get_round($user);

//echo $round;
$in = $_REQUEST['i'];
if(isset($in)) {
	$facebook->redirect("$facebook_canvas_url/install.php?i=$in");
}

//print_r($user);
$user_in_db = is_user_in_db($user);
if($user_in_db ==0) {
	$facebook->redirect("$facebook_canvas_url/install.php");
}

?>
<script>
<!--
  
  function attackship(e, direction) {
	//e.getForm().setDisabled(true); 
	document.getElementById('attackstraight').setDisabled(true);
	document.getElementById('attackleft').setDisabled(true);
	document.getElementById('attackright').setDisabled(true);

	e.setStyle("background", "gray"); 
	document.setLocation("<?php echo $facebook_canvas_url; ?>/attack_merchant_ship_action.php?d=" + direction);

  }
// --!>
</script>
<style>

.#whitelink a {
	color: #FFFFFF;
}

</style>

<?php


print dashboard();


$memcache->set($user . 'merchant_ship_limit', 1, false, 60 * 6);
?>



<?php if($msg):?>
<fb:success>
     <fb:message><?php echo $msg; ?></fb:message>
</fb:success>
<?php endif; ?>	

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

$enemy = $memcache->get($user . 'merchant_ship');
if($enemy == false) {

    $c =  rand($your_cannons/2, $your_cannons);  

    $h = rand(1,10);
    if($h == 1) {
        $coins = rand(2000, 3000);
        $level = rand($your_level + 10, $your_level * 2);
 
    if($c < 40) {
        $c = 40;
    }
    
    }
    else {
	    if($c < 20) {
        	$c = 20;
    	}    
	    $level = rand($your_level - round($your_level/3), $your_level + round($your_level/3));
        
        $coins = rand(400, 700);    
    }
  $enemy = array('level' => $level, 'damage' => '0', 'coin_total' => $coins, 'cannons' => $c);
  $memcache->set($user . 'merchant_ship', $enemy);
  
  //clear the battle history for the attacker
  $memcache->set($user . 'msbh', false);


}


$enemy_level = $enemy['level'];
$enemy_damage = $enemy['damage'];
//$enemy_team = $enemy['team'];
$enemy_cannons = $enemy['cannons'];
$enemy_coins = $enemy['coin_total'];
$enemy_health = $enemy_level - $enemy_damage;




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
<?php success_msg($msg); ?>

<table style='text-align:center; padding:10px; margin:10px; border: 1px dotted black' cellspacing=5 cellpadding=5 border=0>
<tr>

<td align='center' style='border: 1px dotted black; background-color: #3B5998; color: #FFFFFF'>


<span style='text-align:center'>
	<?php echo get_square_profile_pic($user); ?>

</span>

<a href='user_profile.php?id=<?php echo $user; ?>' style='color:#FFFFFF'>Cap'n 
<?php
global $network_id;
if($network_id == 0) {
?>
	<fb:name firstnameonly='true' useyou='false' linked='false' uid="<?php echo $user; ?>" shownetwork='true'/>
<?php
}
else {
	echo get_name_for_id($user);
}
?>
</a>

<p>Level <?php echo $your_type; ?> <?php echo $your_level; ?>  <?php echo ucwords($your_team); ?> Pirate (<?php echo $your_health; ?> health remaining)</p>
<p>Level <?php echo $your_cannons; ?> Cannons</p>
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

		<?php image($merchant_image); ?>


<br>




<p>Level <?php echo $enemy_level; ?>  <?php echo ucwords($enemy_team); ?> Pirate (<?php echo $enemy_health; ?>  health remaining)</p>
<p>Level <?php echo $enemy_cannons; ?> Cannons</p>
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


		<?php image($merchant_image); ?>

<br>
<div style='text-align:center'><strong>Last known position of <?php echo ucwords($enemy_team); ?> Ship</strong></div>



<?php endif; ?>


</td>

<td>

<?php if($enemy_direction == 'straight'): ?>

		<?php image($merchant_image); ?>

<br>
<div style='text-align:center'><strong>Last known position of <?php echo ucwords($enemy_team); ?> Ship</strong></div>



<?php endif; ?>

</td>

<td align='center'>

<?php if($enemy_direction == 'right'): ?>
		<?php image($merchant_image); ?>

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
			<form action="attack_merchant_ship_action.php">
			<input type="hidden" value="<?php echo $enemy_id; ?>" name="enemy_id"/>
			<input type="hidden" value="<?php echo $next_round; ?>" name="r"/>
			<input type="hidden" value="left" name="d"/>

			<input id='attackleft'  onclick = 'attackship(this, "left"); return false;'  style="width:150px; height:65px; font-size: 150%" class="inputsubmit" type="submit" value="FIRE LEFT!!" name="submit"/>
			</form>
		</center>
		
			</td>
			
			<td align='center'>
			<center>
			
			<form action="attack_merchant_ship_action.php">
			<input type="hidden" value="<?php echo $enemy_id; ?>" name="enemy_id"/>
			<input type="hidden" value="<?php echo $next_round; ?>" name="r"/>
			<input type="hidden" value="straight" name="d"/>

			<input id='attackstraight'  onclick = 'attackship(this, "straight"); return false;' style="width:150px; height:65px; font-size: 150%" class="inputsubmit" type="submit" value="FIRE STRAIGHT!!" name="submit"/>
			</form>
			</center>
			
			</td>
			
			
			<td>
			<center>
			
			<form action="attack_merchant_ship_action.php">
			<input type="hidden" value="<?php echo $enemy_id; ?>" name="enemy_id"/>
			<input type="hidden" value="<?php echo $next_round; ?>" name="r"/>
			<input type="hidden" value="right" name="d"/>

			<input id='attackright'  onclick = 'attackship(this, "right"); return false;' style="width:150px; height:65px; font-size: 150%" class="inputsubmit" type="submit" value="FIRE RIGHT!!" name="submit"/>
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
<h2 style="text-align:center; padding-bottom: 10px"><a href="run_away_from_ship.php">Run away!!</a></h2>





</center>	
<?php $h = $memcache->get($user . 'msbh'); ?>

<?php if(!empty($h)) { ?>
<h1 style='text-align:center'>Battle History</h1>
<?php echo $h; ?>

<?php
}

echo adsense_468($user);
echo '<br>';

?>


<?php require_once 'footer.php'; ?>
