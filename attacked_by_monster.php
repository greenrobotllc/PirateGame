

<?php
require_once 'includes.php';
global $DB, $memcache;
global $seadragon, $squid, $deadfish, $crabtopuss;


$hit = $_REQUEST['h'];
$direction = $_REQUEST['d'];
$your_hit = $_REQUEST['u'];
$enemy_hit = $_REQUEST['e'];
$enemy_direction = $_REQUEST['f'];

$monster_type = $memcache->get($user . 'mt');
if($monster_type == FALSE) {

	$ra = rand(0,3);
	if($ra == 0) {
		$monster_type = 'crabtopuss';
		$monster_type_pid = $crabtopuss;
		$memcache->set($user . 'mt', 'crabtopuss');

	}
	else if($ra == 1) {
		$monster_type = 'deadfish';
		$monster_type_pid = $deadfish;
		$memcache->set($user . 'mt', 'deadfish');
	}
	else if($ra == 2) {
		$monster_type = 'squid';
		$monster_type_pid = $squid;
		$memcache->set($user . 'mt', 'squid');
	}
	else {
		$monster_type = 'seamonster';
		$monster_type_pid = $seadragon;
		$memcache->set($user . 'mt', 'seamonster');
	}

}
else {
	if($monster_type == 'seamonster') {
		$monster_type_pid = $seadragon;
	}
	else if($monster_type == 'squid') {
		$monster_type_pid = $squid;
	}
	else if($monster_type == 'deadfish') {
		$monster_type_pid = $deadfish;
	}
	else if($monster_type == 'crabtopuss') {
		$monster_type_pid = $crabtopuss;
	} 
	else {
		$monster_type_pid = $seadragon;
	}
}

redirect_to_index_if_not_secondary($user, "attacked_by_monster");

//update_action($user, "attacked_by_monster");
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

<?php

print dashboard();


$memcache->set($user . 'merchant_ship_limit', 1, false, 60 * 6);
?>



<?php if($msg):?>
<?php success_msg($msg); ?>
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
    $h = rand(1,100);
    if($h == 1) {
		$c =  rand($your_cannons/2, $your_cannons + round($your_cannons/3));  
    	if($c < 75) {
        	$c = 75;
    	}
        $coins = rand(2000, 4000);
        if($your_level > 1000) {
        	$coins = $coins * 2;
        	$c += round($c/4);
        }
      
        $level = rand($your_level + 25, $your_level * 2);
    }
    else {
        $c =  rand($your_cannons/2, $your_cannons);  
    	if($c < 50) {
        	$c = 50;
    	}
    
        $level = rand($your_level - round($your_level/3), $your_level + round($your_level/3));
        $coins = rand(250, 1000);    
        if($your_level > 1000) {
        	$coins = $coins * 2;
          	$c += round($c/4);
      	}
    }
    
    if($level > 500) {
        	if($c < 100) {
        	$c = rand(50,100);
    	}	
    }
 
     if($level > 1000) {
        	if($c < 1000) {
        	$c = rand(100,200);
    	}	
    }   
	//$level = 300;
  $enemy = array('level' => $level, 'damage' => '0', 'coin_total' => $coins, 'cannons' => $c);
  $memcache->set($user . 'merchant_ship', $enemy);
  //clear battle history - new ship!
  $memcache->set($user . 'monster_battle_history', false);

}


$enemy_level = $enemy['level'];
$enemy_damage = $enemy['damage'];
//$enemy_team = $enemy['team'];
$enemy_cannons = $enemy['cannons'];
$enemy_coins = $enemy['coin_total'];
$enemy_health = $enemy_level - $enemy_damage;

$action_type = $_REQUEST['action_type'];

if($hit == 'miss') { //it was a miss
	$msg = "Arrrrrrr... you fired $direction and missed! and caused <b>$your_hit damage</b>.  The monster attacked back, causing you <b>$enemy_hit damage</b>";
	
}
if($action_type == 'parrot') {
	if(empty($enemy_hit)) {
		$msg = "Your parrot dropped some dynamite on the monster for $your_hit damage!</b>";	
	}
	else {
		$msg = "Your parrot dropped some dynamite on the monster for $your_hit damage!<br>The monster attacked back for $enemy_hit damage!";
	}
}
else if($action_type == 'parrotdied') {
	if(empty($enemy_hit)) {
		$msg = "Your parrot caused $your_hit damage, but died in the attack!!</b>";	
	}
	else {
		$msg = "Your parrot caused $your_hit damage, but died in the attack!!<br>The monster attacked back for $enemy_hit damage!";
	}
}
else if($action_type == 'rum') {
	$msg = "Yer crew drank rum and are ready for battle!";	

}
else if(!empty($your_hit) && !empty($enemy_hit)) {
	$msg = "Arrrrrrr... you fired and caused <b>$your_hit damage</b><br>The monster attacked back, causing you <b>$enemy_hit damage</b>";
}
else if($action_type == 'ham') {
	if(!empty($enemy_hit)) {
		$msg = "You ate some ham and healed yerself but the monster attacked you for $enemy_hit damage!</b>";	
	}
	else {
		$msg = "You ate some ham and healed yerself!</b>";
	}
}
else {
	$msg = 'Shiver Me Timbers! A monster is attacking you!';
}


$round = get_round($user);

//echo "round $round";

?>


<center>

<?php success_msg($msg); ?>
     
     <center>

<table width='95%' style='text-align:center; padding:10px; margin:10px; padding-top:0px; margin-top:0px;' cellspacing=5 cellpadding=5 border=0>
<tr>

<td align='center' style='border: 1px dotted black; background-color: #3B5998; color: #FFFFFF; padding:10px' width='180px'>


<span style='text-align:center'>
<table><tr><td>
	<?php echo get_square_profile_pic($user); ?>
</td><td>
<p style='color:#FFFFFF'>
<a href='user_profile.php?id=<?php echo $user; ?>' style='color:#FFFFFF'>Cap'n <?php echo get_first_name_for_id($user); ?>
</a><br>
Level <?php echo $your_type; ?> <?php echo $your_level; ?>  <?php echo ucwords($your_team); ?> Pirate</p>

</td>
</tr>

</table>
</span>



<p><?php echo $your_health; ?> health remaining<br>
Level <?php echo $your_cannons; ?> Cannons<br>
<?php


	$dynamite_total = get_dynamite_count($user);
	$parrot_total = get_parrot_count($user);
	$ham_total = get_ham_count($user);
	$rum_total = get_rum_count($user);
	
	
	if($parrot_total == 1) {
		$parrot_text = ' parrot';
	}
	else {
		$parrot_text = ' parrots';
	}
	if($dynamite_total == 1) {
		$dynamite_text = ' stick of dynamite';
	}
	else {
		$dynamite_text = ' sticks of dynamite';
	}
	if($rum_total == 1) {
		$rum_text = ' bottle of rum';
	}
	else {
		$rum_text = ' bottles of rum';
	}	
 ?>

<p><?php echo $parrot_total; echo $parrot_text; ?><br>
<?php echo $dynamite_total; echo $dynamite_text; ?><br>
<?php echo $ham_total; ?> ham<br>
<?php echo $rum_total;  echo $rum_text; ?></p>

</td>





<td align='center' style='border: 1px dotted black; background-color: #3B5998; color: #FFFFFF'>
<br>
<center>
<?php image($monster_type_pid); ?>
</center>

<center>


<p style='text-align:center'>Level <?php echo $enemy_level; ?>  <?php echo ucwords($enemy_team); ?> Monster (<?php echo $enemy_health; ?>  health remaining)</p>
<p style='text-align:center'>Level <?php echo $enemy_cannons; ?> Attack Power</p>
<p style='text-align:center'>Defeat this monster to continue yer quest for treasure!</p>
<?php if(false): ?>
	<p><?php echo $enemy_coins; ?> coin onboard</p>
<?php elseif (false): ?>
	<p><?php echo $enemy_coins; ?> coins onboard</p>
<?php endif; ?>

</center>

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

<td valign='top'>


<?php 


$rum_required = round(get_crew_count($user) / 4);


if($rum_total > $rum_required && $round == 5) {
?>


	<center>
			<h3>To continue fighting yer crew must drink!</h3>

			<form style='padding-top:10px' action="attack_monster_action.php">
			<input type="hidden" value="<?php echo $enemy_id; ?>" name="enemy_id"/>
			<input type="hidden" value="<?php echo $next_round; ?>" name="r"/>
			<input type="hidden" value="drink" name="rum"/>

			<input style="width:190px; height:65px; font-size: 125%" class="inputsubmit" type="submit" value="DRINK <?php echo $rum_required; ?> RUM" name="submit"/>
			</form>
		</center>


<?php

}
else {

?>

	<center>
			<form style='padding-top:10px' action="attack_monster_action.php">
			<input type="hidden" value="<?php echo $enemy_id; ?>" name="enemy_id"/>
			<input type="hidden" value="<?php echo $next_round; ?>" name="r"/>
			<input type="hidden" value="left" name="d"/>

			<input style="width:190px; height:50px; font-size: 125%" class="inputsubmit" type="submit" value="FIRE CANNONS!" name="submit"/>
			<input type="hidden" value="FIRECANNONS" name="weird"/>
		</form>

		</center>
		<br>
			
			<?php if($dynamite_total > 0 && $parrot_total > 0) { ?>
			
			<center>
						<form action="attack_monster_action.php">
			<input type="hidden" value="<?php echo $enemy_id; ?>" name="enemy_id"/>
			<input type="hidden" value="<?php echo $next_round; ?>" name="r"/>
			<input type="hidden" value="straight" name="d"/>

			<input style="width:190px; height:50px; font-size: 125%" class="inputsubmit" type="submit" value="DYNAMITE PARROTS!!" name="submit"/>
			<input type="hidden" value="DYNAMITEPARROTS" name="weird"/>
		</form>
			<br>
			</center>
			
			<?php } ?>
			
	
			<?php if($ham_total > 0) { ?>
			<center>
			
			<form action="attack_monster_action.php">
			<input type="hidden" value="<?php echo $enemy_id; ?>" name="enemy_id"/>
			<input type="hidden" value="<?php echo $next_round; ?>" name="r"/>
			<input type="hidden" value="right" name="d"/>

			<input  style="width:190px; height:50px; font-size: 125%" class="inputsubmit" type="submit" value="EAT HAM" name="submit"/>
			<input type="hidden" value="EATHAM" name="weird"/>

			</form>

			</center>
			<?php
			}
			?>
		
		
		<?php
}
?>

			</td>
			
			<td align='center' valign='top'>
				<h1 style='text-align:center'>Battle History</h1>
				<?php $h = $memcache->get($user . 'monster_battle_history'); ?>
				<?php echo $h; ?>

			</td>
			
			

</tr>
</table>


			
		
</center>
<h2 style="text-align:center; padding-bottom: 10px">You cannot run away from this attacker!</h2><br>





</center>	

<?php

echo adsense_468($user);
echo '<br>';
//require_once "my_pirate.inc.php";

//require_once "world_stats.inc.php";
//set_profile($user);




?>


<?php require_once 'footer.php'; ?>
