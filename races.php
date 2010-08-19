<?php
//error_reporting(E_ALL);

require_once 'includes.php';

redirect_if_action($user);

$was_bombed = get_was_bombed($user);
if($was_bombed != "" && $was_bombed != 0 ) {
	$facebook->redirect("you_were_bombed.php");
}
$was_attacked = get_was_attacked($user);
if($was_attacked != "" && $was_attacked != 0) {
      $facebook->redirect("you_were_attacked.php");
}

print dashboard();
?>


<?php
$action = $_REQUEST['action'];
$item = $_REQUEST['item'];
$num = $_REQUEST['num'];

if($item == 11) {
	$animal_type = 'parrot';
	$item_picture = $item_parrot_small;
	$animal_escape = 'parrot flies away';
	$animal_char = 'p';
	$animal_cost = 250;
}
else {
	$animal_type = 'monkey';
	$item_picture = $item_monkey_small;			
	$animal_escape = 'monkey runs away';
	$animal_char = 'm';
	$animal_cost = 100;
	
}
			
$item_count = 30;  //if we have more items than this, increase this #


$team = get_team($user);

 
$msg = $_REQUEST['msg'];
if($msg == 'not-enough-money') {
	echo "<center><fb:error><fb:message>You don't have enough coins on yer ship to enter the race!<br><a href='retrieve_coins.php'>Dig up some buried coins.</a></fb:message></fb:error></center>";
}
?>

<div style="padding: 15px;">
	<center><h1>Welcome to Pirate Pet Races!</h1>
	<h4>Every half hour parrots and monkeys race for gold!</h4>
	
	</center>



</div>

<fb:tabs>
	<fb:tab_item href="<?php echo $facebook_canvas_url; ?>/races.php" title="Pet Races" <?php if($action == "upcoming" or $action == "") { echo 'selected="true"'; } ?>/>	<fb:tab_item href="<?php echo $facebook_canvas_url; ?>/races.php?action=entrants" title="Entrants In This Race" <?php if($action == "entrants") { echo 'selected="true"'; } ?>/>
	<fb:tab_item href="<?php echo $facebook_canvas_url; ?>/races.php?action=results" title="Past Race Results" <?php if($action == "results") { echo 'selected="true"'; } ?>/>

	<fb:tab_item href="<?php echo $facebook_canvas_url; ?>/races.php?action=discussion" title="Discussion" align="right" <?php if($action == "discussion") { echo 'selected="true"'; } ?>/>
</fb:tabs>

<div style="padding: 10px; background-color: #f7f7f7;">
	<?php if($action == "upcoming" or $action == "") { ?>
		<table cellpadding="0" cellspacing="0" border="0">
			<tr>
				<td width="150" valign="top" rowspan="2" style="padding: 0px 10px 0px 0px;">
					<h3 style="padding: 0px 0px 5px 0px;">Pick a Race:</h3>
					<div style="background-color: #FFFFFF; border: 1px solid grey; padding: 5px 5px 5px 5px; height:279px;">
					<ul style="margin: 0pt; padding-left: 5px; list-style-type: none;">
						<li <?php if(($item == 7) or !isset($item))  { echo 'style="background-color: #3B5998; padding: 3px 3px 3px 3px;"'; } ?>><a href="?action=upcoming&item=7" <?php if($item == 7 or !isset($item)) { echo 'style="color: #FFFFFF; font-weight: bold;"'; } ?>>Monkeys</a></li>
				

						<li <?php if($item == 11) { echo 'style="background-color: #3B5998; padding: 3px 3px 3px 3px;"'; } ?>><a href="?action=upcoming&item=11" <?php if($item == 11) { echo 'style="color: #FFFFFF; font-weight: bold;"'; } ?>>Parrots</a></li>					
					</ul>
					<br><br><br>
				<?php echo adsense_125_125($user); ?><br>

				
					</div>
				</td>
				<td width="460" valign="top" height="100%" style="padding: 0px 0px 3px 0px;">
			
					<div style="background-color: #FFFFFF; height:300px; border: 1px solid grey; padding: 5px 5px 5px 5px;">
					
		<h2 style='text-align:center; padding:10px'>Next <?php echo $animal_type; ?> race in <?php echo get_race_time_left(); ?></h2>		

		<table cellspacing='5' cellpadding='5'><tr>
		<td>
		
		<?php if(get_number_entries($animal_char) == 1) {
		?>
		<h3>So far, <?php echo get_number_entries($animal_char); ?> <?php echo $animal_type; ?> has entered this race!</h3>
		<?php
		}
		else {
		?>
		<h3>So far, <?php echo get_number_entries($animal_char); ?> <?php echo $animal_type; ?>s have entered this race!</h3>
		<?php
		}
		?>
				
		<h3>Jackpot amount: <?php echo number_format(get_jackpot_amount($animal_char)); ?> coins</h3>		
		
			<br>
			<h2 style='text-decoration:underline'>Race Rules</h2>
			<h4>There is a new race every half hour.</h4>
			<h4>Limit one entry per race.</h4>
			<h4>All <?php echo $animal_type; ?>s have an equal chance at winning.</h4>
			<h4>If you win the jackpot, your <?php echo $animal_escape; ?>!</h4>
			<br>
			
			
			<?php
			if(user_can_enter_race($user, $animal_char)) { 
			
			if(not_enough_to_race($user, $animal_char))
			{
			?>
				<h3>You need at least one <?php echo $animal_type; ?> to enter the <?php echo $animal_type; ?> race!</h3><br> 
			<?php		
			}
			
			else {
			?>
				
			<center>

			<h3>Do you want to enter the <?php echo $animal_type; ?> race?</h3>
			<h3>It will cost <?php echo $animal_cost; ?> coins!</h3>
<br>
	<h4>You have <?php echo get_coin_total($user); ?> coins<br><a href="<?php echo $facebook_canvas_url; ?>/retrieve_coins.php"><?php echo get_coin_total_buried($user); ?> coins buried</a></h4><br>


			<div style='font-size:100%; padding:10px;'>
			
			<form action='races_action.php'>
				<input type='hidden' name='type' value='<?php echo $animal_char; ?>'>
				<input onclick = 'this.setDisabled(true); this.setStyle("background", "gray"); document.setLocation("<?php echo $facebook_canvas_url; ?>/races_action.php?type=<?php echo $animal_char; ?>"); return false;' type='submit' class='inputsubmit' value='Enter the Race!' name='bet'><br>
			</form>
			
			</div>
			</center>
			
			<?php } } 
			else if(not_enough_to_race($user, $animal_char)) {
			?>
			<h3>You need at least one <?php echo $animal_type; ?> to enter the <?php echo $animal_type; ?> race!</h3><br>
			<?
			}
			else {
			?>
			<br>
			<h3>You entered a <?php echo $animal_type; ?> in the race!</h3><br>
			<h3>At the end of the race the winner gets the jackpot!</h3>
		
			
			<?php
			}
			?>
			
			
			</td>
			<td>

			<fb:photo pid='<?php echo $item_picture; ?>' uid='<?php echo $image_uid; ?>' />
			</td></tr>
				</table>
				
						</tr>
					</table>
					</div>
				</td>
			</tr>

		</table>
	<?php } else if($action == "sell") { ?>
		<table cellpadding="0" cellspacing="0">
			<tr>
				<td width="150"><h3 style="padding: 0px 0px 5px 0px;">&nbsp;&nbsp;Item</h3></td>
				<td width="108"><h3 style="padding: 0px 0px 5px 0px;">Sell Qty</h3></td>
				<td width="140"><h3 style="padding: 0px 0px 5px 0px;">Lowest Price</h3></td>
				<td width="90"><h3 style="padding: 0px 0px 5px 0px;">Selling Price</h3></td>
			</tr>
		</table>
		<div style="background-color: #FFFFFF; border: 1px solid grey; padding: 5px 5px 5px 5px;">

		</div>
	<?php } else if($action == "sale_confirmation" or $action == "update_confirmation") { ?>
	
		<?php 
			$booty_data = get_booty_data_from_id($item);
		?>
		<center>
		<h3><?php if($action == "sale_confirmation") { echo "Sale"; } else { echo "Update"; } ?> Confirmation</h3><br>
	</div>
		</center>
	<?php }
	
	 else if($action == "entrants") {
	 
	  ?>

	
	
	
	
	
			<table cellpadding="0" cellspacing="0" border="0">
			<tr>
				<td width="150" valign="top" rowspan="2" style="padding: 0px 10px 0px 0px;">
					<h3 style="padding: 0px 0px 5px 0px;">Pick a Race:</h3>
					<div style="background-color: #FFFFFF; border: 1px solid grey; padding: 5px 5px 5px 5px; height:525px;">
					<ul style="margin: 0pt; padding-left: 5px; list-style-type: none;">
						<li <?php if(($item == 7) or !isset($item))  { echo 'style="background-color: #3B5998; padding: 3px 3px 3px 3px;"'; } ?>><a href="<?php echo $facebook_canvas_url; ?>/races.php?action=entrants&item=7" <?php if($item == 7 or !isset($item)) { echo 'style="color: #FFFFFF; font-weight: bold;"'; } ?>>Monkeys</a></li>
				

						<li <?php if($item == 11) { echo 'style="background-color: #3B5998; padding: 3px 3px 3px 3px;"'; } ?>><a href="?action=entrants&item=11" <?php if($item == 11) { echo 'style="color: #FFFFFF; font-weight: bold;"'; } ?>>Parrots</a></li>					
					</ul>
					<br><br><br>
					<?php echo adsense_125_125($user); ?>
				<div style='margin-left:20px; padding-top:20px; padding-bottom:20px;'><fb:photo pid='<?php echo $item_picture; ?>' uid='<?php echo $image_uid; ?>' /></div>
				<?php echo adsense_125_125($user); ?><br>
				
					</div>
				</td>
				<td width="460" valign="top" height="100%" style="padding: 0px 0px 3px 0px;">
			
					<div style="background-color: #FFFFFF; border: 1px solid grey; padding: 5px 5px 5px 5px;">
					
	<center>
		<h2 style='text-align:center; padding:10px'><?php echo ucwords($animal_type); ?> Race Contestants</h2>		
	
		<table cellspacing='5' cellpadding='5'><tr>
		<td>
		
		
		<?php
			echo "<center><table style='padding: 5px; padding-top: 10px; text-align:center;' cellpadding='0' cellspacing='0' border='0'>";
			$r = $DB->GetArray('select * from races, users where completed = 0 and type = ? and users.id = races.user_id', array($animal_char));
			foreach($r as $key => $value) {		
									$id = $value['user_id'];
									$level = $value['level'];
									$team = $value['team'];
									$team = ucwords($team);
									$buried_coin_total = $value['buried_coin_total'];
									$rank = $key + 1;
									echo "<tr><td style='padding: 5px 10px 0px 5px;'>";
									echo "<a href='CHANGEME/user_profile.php?user=$id'><fb:profile-pic size='square' uid='$id' linked='false'/></a>";
									$n_buried_coin_total = number_format($buried_coin_total);
									echo "</td><td><h4><a href='CHANGEME/user_profile.php?user=$id'><fb:name uid='$id' linked='false' /></a><br>$team Pirate<br>level: $level gold: $n_buried_coin_total<h4>";
									echo "</td></tr>";
								}
								echo "</table></center>";
							
							
							?>
			
			</td>
			<td valign='top'>
			<div style='padding-bottom:80px'>
			<fb:photo pid='<?php echo $item_picture; ?>' uid='<?php echo $image_uid; ?>' />		</div>
			</td></tr>
				</table>
				
						</tr>
					</table>
					</div>
				</td>
			</tr>

		</table>
	
	</center>


	
	
	
	
	
	
	
	
	
	
	
		
		
	<?php } 
	
	
	
	




 else if($action == "results") {
	 
	  ?>

	
	
	
	
	
	
	
	
			<table cellpadding="0" cellspacing="0" border="0">
			<tr>
				<td width="150" valign="top" rowspan="2" style="padding: 0px 10px 0px 0px;">
					<h3 style="padding: 0px 0px 5px 0px;">Pick a Race:</h3>
					<div style="background-color: #FFFFFF; border: 1px solid grey; padding: 5px 5px 5px 5px; height:550px;">
					<ul style="margin: 0pt; padding-left: 5px; list-style-type: none;">
						<li <?php if(($item == 7) or !isset($item))  { echo 'style="background-color: #3B5998; padding: 3px 3px 3px 3px;"'; } ?>><a href="?action=results&item=7" <?php if($item == 7 or !isset($item)) { echo 'style="color: #FFFFFF; font-weight: bold;"'; } ?>>Monkeys</a></li>
				

						<li <?php if($item == 11) { echo 'style="background-color: #3B5998; padding: 3px 3px 3px 3px;"'; } ?>><a href="?action=results&item=11" <?php if($item == 11) { echo 'style="color: #FFFFFF; font-weight: bold;"'; } ?>>Parrots</a></li>					
					</ul>
					<br><br><br>
					
					<?php echo adsense_125_125($user); ?>
				<div style='margin-left:20px; padding-top:20px; padding-bottom:20px;'><fb:photo pid='<?php echo $item_picture; ?>' uid='<?php echo $image_uid; ?>' /></div>
				
					<?php echo adsense_125_125($user); ?>
			
			
			
			
					</div>
				</td>
				<td width="460" valign="top" height="100%" style="padding: 0px 0px 3px 0px;">
			
					<div style="background-color: #FFFFFF; border: 1px solid grey; padding: 5px 5px 5px 5px;">
					
	<center>
		<h2 style='text-align:center; padding:10px'><?php echo ucwords($animal_type); ?> Race Winners (last 50)</h2>		
	
		<table cellspacing='5' cellpadding='5'><tr>
		<td>
		
		
		<?php
			echo "<center><table style='padding: 5px; padding-top: 10px; text-align:center;' cellpadding='0' cellspacing='0' border='0'>";
			$r = $DB->GetArray('select race_results.created_at c, users.team, users.level, race_results.user_id, users.id, race_results.jackpot, race_results.created_at from race_results, users where type = ? and users.id = race_results.user_id order by race_results.created_at desc limit 50', array($animal_char));
			foreach($r as $key => $value) {		
									$id = $value['user_id'];
									$level = $value['level'];
									$team = $value['team'];
					$created_at = date("F j, Y h:i", strtotime($value['c']));
									$jackpot = $value['jackpot'];
									$team = ucwords($team);
									$buried_coin_total = $value['buried_coin_total'];
									$rank = $key + 1;
									echo "<tr><td style='padding: 5px 10px 0px 5px;'>";
									echo "<a href='CHANGEME/user_profile.php?user=$id'><fb:profile-pic size='square' uid='$id' linked='false'/></a>";
									$n_jackpot = number_format($jackpot);
									echo "</td><td><h3 style='margin-top:15px'><a href='CHANGEME/user_profile.php?user=$id'><fb:name uid='$id' linked='false' ifcantsee = 'A $team Pirate' /></a></h3><h3>Jackpot: $n_jackpot coins</h3><h3>$created_at</h3><br>";
									echo "</td></tr>";
								}
								echo "</table></center>";
							
							
							?>
			
			</td>
			<td valign='top'>

			<fb:photo pid='<?php echo $item_picture; ?>' uid='<?php echo $image_uid; ?>' />
			</td></tr>
				</table>
				
						</tr>
					</table>
					</div>
				</td>
			</tr>

		</table>
	
	</center>


	
	
	
	
	
	
	
	
	
	
	
		
		
	<?php } 
	
	
	
	
	







	
	else if($action == "discussion") { ?>
		<?php
			$moderators = get_moderators();
			$banned = get_banned();

			if (in_array($user, $moderators )) {
				$candelete = 'true';
			}
			else {
				$candelete = 'false';
			}

			if (in_array($user, $banned )) {
				$canpost = 'false';
			}
			else {
				$canpost = 'true';
			}
		?>

		<fb:comments showform="true" xid="pirates_racing_wall" canpost="<?php echo $canpost; ?>" candelete="<?php echo $candelete; ?>" returnurl="<?php echo $facebook_canvas_url; ?>/races.php?action=discussion">
   			<fb:title>Pirate Pet Racing Board</fb:title>
 		</fb:comments>
	<?php } ?>
</div>
<?php if(false) { ?>
<center>
<fb:success>
     <fb:message>Congratulations to the latest Pirate Race Winners          
     <br>
     <a href='http://facebook.com/profile.php?id=<?php echo get_past_winner_id("parrot"); ?> '><fb:name uid='<?php echo get_past_winner_id("parrot"); ?>' useyou='false' firstnameonly='true' linked='false'/>'s</a> Parrot won <?php echo get_past_winner_amount("parrot"); ?> coins!
     <br> 
     <a href='http://facebook.com/profile.php?id=<?php echo get_past_winner_id("monkey"); ?> '><fb:name uid='<?php echo get_past_winner_id("monkey"); ?> ' useyou='false' firstnameonly='true' linked='false'/>'s</a> Monkey won <?php echo get_past_winner_amount("monkey"); ?>  coins!<br></fb:message>
</fb:success>
</center>
<?php } ?>

<br>


<h3 style="text-align:center; padding-top: 10px; padding-bottom: 5px"><a href="tavern.php">Go to the tavern</a></h3>

<h3 style="text-align:center; padding-top: 5px; padding-bottom: 10px"><a href="index.php">Go back to sea</a>  -adventure, danger and treasure await</h3>
<br>




</div>
<?php 
	//print user_has_item(1807687, 1);
	if($user != 557032064 && $user != 123) {
		print adsense_468($user);
	}
	echo '<br>';
?>
<?php 	

//require_once 'ad_bottom.inc.php';

require_once 'footer.php';
?>