<?php

require_once 'includes.php';

//Redirect to install if user has not added the application. 
$user_in_db = is_user_in_db($user);
if($user_in_db ==0) {
    $facebook->redirect("$facebook_canvas_url/install.php");
}

$in = $_REQUEST['i'];
if(isset($in)) {
	$facebook->redirect("$facebook_canvas_url/install.php?i=$in");
}

//Dashboard
print dashboard();

//get the selected tab
$action = $_REQUEST['action'];
$profile_id = $_REQUEST['user'];
$see_all_races = $_REQUEST['see_all_races'];

if(!isset($profile_id)) {
	$profile_id = $user;
}

$team = get_team($profile_id);
$level = get_level($profile_id);

?>

<style type="text/css">
    .full-column { padding-left: 5px; padding-right: 5px; }
    .left-column { float: left; width: 100%; margin-right: -400px; }
    .left-column-content { padding: 0pt 10px 10px 5px; margin-right: 400px; }
    .right-column { border-left: 1px solid rgb(204, 204, 204); float: right; width: 380px; padding-left: 12px; padding-right: 5px;}
    .box_head { border-top: 1px solid rgb(59, 89, 152); border-bottom: 1px solid rgb(204, 204, 204); padding: 2px 4px; background: rgb(216, 223, 234) none repeat scroll 0% 50%; color: rgb(59, 89, 152); font-weight: bold; }
    .box-content { padding: 5px; background: #FFFFFF; border-left: 1px solid rgb(204, 204, 204); border-right: 1px solid rgb(204, 204, 204); border-bottom: 1px solid rgb(204, 204, 204); }
    .current-level-box ul.no-bullets { padding-left: 5px;}
    .left-column-equal-size { float: left; width: 100%; margin-right: -320px; }
    .left-column-content-equal-size { padding: 0pt 10px 10px 5px; margin-right: 320px; }
    .right-column-equal-size { border-left: 1px solid rgb(204, 204, 204); float: right; width: 300px; padding-left: 12px; padding-right: 5px;}
</style>

<div style="padding: 15px;">
	<center><h1>Pirate Profile</h1>
	<table border="0">
		<tr>
			<td>
				<table style="padding: 5px 0px 0px 0px;">
					<tr>
						<td valign="center">
							<?php 
							echo get_square_profile_pic($profile_id);
							
							 ?>
							
						</td>
						<td valign="center"><h2>
						<?php if($network_id == 0) { ?>
							<fb:name uid="<?php echo $profile_id; ?>" useyou="false" ifcantsee="Anonymous User"/>
							
						<?php }
						else {
							echo get_name_for_id($profile_id);
						}
						 ?>
						</h2>
						<h4><?php echo ucwords($team); ?> Level: <?php echo $level; ?><br>Coins: <?php echo number_format(get_coin_total($profile_id)); ?> Buried: <?php echo number_format(get_coin_total_buried($profile_id)); ?><br>Pirate since <?php echo get_date_joined($profile_id); ?></h4></td>
					</tr>
				</table>
			</td>
			<?php if($user != $profile_id) { ?>
				<td style="padding: 0px 0px 0px 20px; text-align: center;">
					<?php global $network_id; 
					if($network_id == 0) { ?>
	<form action="<?php echo "$facebook_canvas_url/user_profile.php?user=$user"; ?>" method="post">
					<?php 
					}
					else 
					{
					?>
	<form action="<?php echo "$facebook_canvas_url/user_profile.php?user=$user&$query_string"; ?>" method="post">
					<?php
					}
					?>
						<input type="Submit" class='inputsubmit' value="Back to your profile" name="back_to_profile">
					</form>
				</td>
			<?php } ?>
		</tr>
	</table>
</div>
	
	
 <?php if($network_id == 1) {  
 	$action = $_REQUEST['action'];
 ?>
  <div class="tabArea">
  <a <?php href('user_profile.php', "?action=general&user=$profile_id"); ?> class="tab <?php if($action == 'general' || $action == ''){ echo 'activeTab'; } ?>">General</a>
  <a <?php href('user_profile.php', "?action=fighting&user=$profile_id"); ?>  class="tab <?php if($action == 'fighting'){ echo 'activeTab'; } ?>">Fighting</a>
  <a <?php href('user_profile.php', "?action=inventory&user=$profile_id"); ?> class="tab <?php if($action == 'inventory'){ echo 'activeTab'; } ?> ">Booty</a>
  <a <?php href('user_profile.php', "?action=fellow_pirates&user=$profile_id"); ?> class="tab <?php if($action == 'fellow_pirates'){ echo 'activeTab'; } ?>">Friends</a>
</div>


	<?php
	} else {
	?>
<fb:tabs>
	<fb:tab_item href="<?php echo $facebook_canvas_url; ?>/user_profile.php?action=general&user=<?php echo $profile_id; ?>" title="General" <?php if($action == "general" or $action == "") { echo 'selected="true"'; } ?>/>
	<fb:tab_item href="<?php echo $facebook_canvas_url; ?>/user_profile.php?action=fighting&user=<?php echo $profile_id; ?>" title="Fighting" <?php if($action == "fighting") { echo 'selected="true"'; } ?>/>
	<fb:tab_item href="<?php echo $facebook_canvas_url; ?>/user_profile.php?action=inventory&user=<?php echo $profile_id; ?>" title="Booty" <?php if($action == "inventory") { echo 'selected="true"'; } ?>/>
	<fb:tab_item href="<?php echo $facebook_canvas_url; ?>/user_profile.php?action=fellow_pirates&user=<?php echo $profile_id; ?>" title="Friends" <?php if($action == "fellow_pirates") { echo 'selected="true"'; } ?>/>

	<fb:tab_item href="<?php echo $facebook_canvas_url; ?>/user_profile.php?action=discussion&user=<?php echo $profile_id; ?>" title="Discussion" align="right" <?php if($action == "discussion") { echo 'selected="true"'; } ?>/>
</fb:tabs>

<?php } ?>
<div style="padding: 10px; background-color: #f7f7f7;">
	<?php if($action == 'general' or $action == "") { ?>
	<div style="height: 1px;"></div>
		<div class="left-column">
			<div class="left-column-content">
    			<div class="clearfix current-level-box">
  					<div class="box_head">
  						Pirate Rank
  					</div>
  					
  					<?php $r = $DB->GetRow('select * from user_ranks where user_id = ?', array($profile_id)); 
  					//print_r($r); 
  					
  					$overall = $r['overall'];
  					$most_coins = $r['most_coins'];
  					$highest_level = $r['highest_level'];
  					
  					$overall_team = $r['overall_team'];
  					$most_coins_team = $r['most_coins_team'];
  					$highest_level_team = $r['highest_level_team'];

  					?>
  					
  					<div class="clearfix box-content" style="text-align:left;">
  						<center><h5 style="padding: 0px 0px 5px 0px;"><a <?php href('leaderboard.php'); ?> ">Go to Leaderboard</a></h5></center>
  						<table cellpadding="0" cellspacing="0" border="0">
  							  	<tr><td style="padding: 2px 0px; background-color: rgb(255, 255, 204); text-align: center;"><h4>Overall Rank</h4></td></tr>
  							  	
  							  	<tr><td><h4>Overall: <?php if($overall == 0) { echo "unranked"; } else { ?> <a <?php href('leaderboard.php', '?value=overall&action=overall&page_num=' . floor($overall / 50)); ?>  ><?php echo number_format($overall); } ?></h4></td></tr>
  								<tr><td><h4>Most Coins: <?php if($most_coins == 0) { echo "unranked"; } else { ?> <a <?php href('leaderboard.php', '?value=mostcoins&action=overall&page_num=' . floor($most_coins / 50)); ?>  ><?php echo number_format($most_coins); } ?></a></h4></td></tr>
  								<tr><td><h4 style='padding-bottom:5px'>Highest Level:  <?php if($highest_level == 0) { echo "unranked"; } else { ?> <a <?php href('leaderboard.php', '?value=level&action=overall&page_num=' . floor($highest_level / 50)); ?>  ><?php echo number_format($highest_level); } ?></a></h4></td></tr>


  							  	<tr><td width="400" style="padding: 2px 0px; background-color: #ffffcc; text-align: center;"><h4><?php echo ucwords($team); ?> Rank</h4></td></tr>
								<tr><td><h4><?php echo ucwords($team); ?> Overall: <?php if($overall_team == 0) { echo "unranked"; } else {  ?> <a <?php href('leaderboard.php', '?value=' . $team . '_overall&action=overall&page_num=' . floor($overall_team / 50)); ?>  ><?php echo number_format($overall_team); } ?></a></h4></td></tr>
  								<tr><td><h4><?php echo ucwords($team); ?> Most Coins: <?php if($most_coins_team == 0) { echo "unranked"; } else { ?> <a <?php href('leaderboard.php', '?value=' . $team . '_overall&action=overall&page_num=' . floor($most_coins_team / 50)); ?>  ><?php echo number_format($most_coins_team); } ?></a></h4></td></tr>
  								<tr><td><h4><?php echo ucwords($team); ?> Highest Level:  <?php if($highest_level_team == 0) { echo "unranked"; } else { ?> <a  <?php href('leaderboard.php', '?value=' . $team . '_overall&action=overall&page_num=' . floor($highest_level_team / 50)); ?>  ><?php echo number_format($highest_level_team); } ?></a></h4></td></tr>


  						</table>
  					</div>
  				</div>
  				
  				
  				
  				
  				<br>
  				
  				
  				
  				
  				
  				
  				<?php 
  				global $network_id;
  				
  				if($network_id == 0) { ?>
  				
  				
    			<div class="clearfix current-level-box">
  					<div class="box_head">
  						Pet Races Won
  					</div>
  					<div class="clearfix box-content" style="text-align:left;">
  						<center><h5 style="padding: 0px 0px 5px 0px;"><a <?php href('races.php'); ?> ">Go to Pet Races</a></h5></center>
  						<table cellpadding="0" cellspacing="0" border="0">
  							<?php $races_array = get_races_won_by_id($profile_id);
  								
  								for($i = 0; $i < count($races_array); $i++) {
  									if($races_array[$i]['type'] == 'm') {
  										$total_won_monkey += $races_array[$i]['jackpot'];
  									} else if($races_array[$i]['type'] == 'p') {
  										$total_won_parrot += $races_array[$i]['jackpot'];
  									}
  									
  									$total_won += $races_array[$i]['jackpot'];
  								}
  							?>
  								<tr><td><h4>Total Won: <?php echo number_format($total_won); ?></h4></td></tr>
  								<tr><td><h4>Monkey Won: <?php echo number_format($total_won_monkey); ?></h4></td></tr>
  								<tr><td style="padding: 0px 0px 5px 0px;"><h4>Parrot Won: <?php echo number_format($total_won_parrot); ?></h4></td></tr>
  							<?php
  								for($i = 0; $i < count($races_array) and ($i < 5 or $see_all_races == "show_all"); $i++) {
  								if($races_array[$i]['type'] == 'm') {
  									$race_type = "Monkey";
  								} else if($races_array[$i]['type'] == 'p') {
  									$race_type = "Parrot";
  								} else {
  									$race_type = $races_array[$i]['type'];
  								}
  							?>
  								<tr>
  									<td width="400" style="background-color: #ffffcc; text-align: center; padding: 2px 0px 2px 0px;"><h4><?php echo $race_type; ?></h4></td>
  								</tr><tr>
  									<td><h4>Jackpot: <?php echo number_format($races_array[$i]['jackpot']); ?></h4></td>
  								</tr><tr>
  									<td style="padding: 0px 0px 5px 0px;"><h4>Date: <?php echo date("m/j/y g:i a", strtotime($races_array[$i]['created_at'])); ?></h4></td>
  								</tr>
  							<?php } ?>
  							<?php if(count($races_array) > 5) { ?>
  							<tr>
  								<td style="text-align: right; border-top: 1px solid lightgrey;"><h5><a href="user_profile.php?user=<?php echo $profile_id; ?>&see_all_races=<?php if($see_all_races == "show_all") { echo "hide"; } else { echo "show_all"; } ?>"><?php if($see_all_races == "show_all") { echo "Show only most recent "; } else { echo "See all pet races "; } ?>-></a></h5></td>
  							</tr>
  							<?php } ?>
  						</table>
  					</div>
  				</div>
  				
  				<?php } ?>
  				
  				
  				
  				
  				
  			</div>
		</div>

		<div class="right-column" style="padding-bottom: 10px;">
			<div class="box_head">Ship Stats</div>
			<div class="box-content">
				<center>
				<table cellspacing="0" cellpadding="3">
					<tr>
						<td>
							<?php
								if($team == "bucaneer" or $team == "buccaneer") {
									$ship_image = $ship_bucaneer_175_image;
								} else if($team == "corsair") {
									$ship_image = $ship_corsair_175_image;
								} else if($team == "barbary") {
									$ship_image = $ship_barbary_175_image;
								}
							?>
								<?php image($ship_image); ?>
						</td><td valign="center">
							<table cellspacing="5">
								<tr>
									<td style="background-color: #FFFFFF; border: 1px solid lightgrey; text-align: center; padding: 0px 5px 0px 5px;">
										<h2>Cannons</h2>
									</td><td style="background-color: #FFFFFF; border: 1px solid lightgrey; text-align: center; padding: 0px 5px 0px 5px;">
										<h2><?php $cannons = get_cannons($profile_id); if($cannons != false or $cannons != 0) { echo $cannons; } else { echo "0"; } ?></h2>
									</td>
								</tr><tr>
									<td style="background-color: #FFFFFF; border: 1px solid lightgrey; text-align: center; padding: 0px 5px 0px 5px;">
										<h2>Sails
									</td><td style="background-color: #FFFFFF; border: 1px solid lightgrey; text-align: center; padding: 0px 5px 0px 5px;">
										<h2><?php $sails = get_sails($profile_id); if($sails != false or $sails != 0) { echo $sails; } else { echo "0"; } ?></h2>
									</td>
								</tr><tr>
									<td style="background-color: #FFFFFF; border: 1px solid lightgrey; text-align: center; padding: 0px 5px 0px 5px;">
										<h2>Hull
									</td><td style="background-color: #FFFFFF; border: 1px solid lightgrey; text-align: center; padding: 0px 5px 0px 5px;">
										<h2><?php $hull = get_hull($profile_id); if($hull != false or $hull != 0) { echo $hull; } else { echo "0"; } ?></h2>
									</td>
								</tr><tr>	
									<td style="background-color: #FFFFFF; border: 1px solid lightgrey; text-align: center; padding: 0px 5px 0px 5px;">
										<h2>Crew</h2>
									</td><td style="background-color: #FFFFFF; border: 1px solid lightgrey; text-align: center; padding: 0px 5px 0px 5px;">
										<h2><?php $crew = get_crew_count($profile_id); if($crew != false or $crew != 0) { echo $crew; } else { echo "0"; } ?></h2>
									</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
				</center>
			</div>
		</div>

	<?php } else if($action == 'fighting') { ?>
	
	
	 	<div class="left-column-equal-size">
			<div class="left-column-content-equal-size">
    			<div class="clearfix current-level-box">
  					<div class="box_head">
  						Last 20 Pirates You've Attacked
  					</div>
  					<div class="clearfix box-content" style="text-align:left;">
					<center>
					<table cellpadding="0" cellspacing="0" border=0 style="text-align: center;">
	<?php	
	
		$attacks = $DB->GetArray("select * from battles where user_id = ? and result != 'p' order by created_at desc limit 20", array($profile_id));
	
		if(count($attacks) == 0) {
			if($user == $profile_id) {
				echo "<h1 style='text-align:center; padding: 5px 5px 5px 5px;'>You haven't been attacked yet.</h1>";
			} else {
				echo "<h1 style='text-align:center; padding: 5px 5px 5px 5px;'>This user hasn't been attacked yet.</h1>";
			}
		}
		else {
			foreach($attacks as $key => $value) {
				$id = $value['user_id_2'];
				$score = $value['b'];
				$level = $value['level'];
				$created_at = date("n/j/y g:i a", strtotime($value['created_at']));
				$buried_coin_total = $value['buried_coin_total'];
				$rank = $key + 1;
				$result = $value['result'];
		
				$gold_change = $value['gold_change'];
			
				if($result == 'w') {
					$winorlose = 'win&nbsp;';
					$wonorlost = 'won';
				}
				else if($result == 'l') {
					$winorlose = 'lose';
					$wonorlost = 'lost';
				}
				else {	
					$winorlose = 'tie&nbsp;&nbsp;';
					$wonorlost = 'tied';
				}
				$buried_coin_total = number_format($buried_coin_total);
	?>					
					
						<tr>
							<td style="padding: 5px 10px 0px 5px; <?php if($wonorlost == "won") { echo "background-color: #bdf9da;"; } else if($wonorlost == "lost") { echo "background-color: #f9c2c2;"; } ?>">
								<a href="<?php echo $facebook_canvas_url; ?>/user_profile.php?user=<?php echo $id; ?>"><fb:profile-pic size="square" uid="<?php echo $id; ?>" linked="false"/></a>
							</td><td style="padding: 0px 5px 0px 0px; <?php if($wonorlost == "won") { echo "background-color: #bdf9da;"; } else if($wonorlost == "lost") { echo "background-color: #f9c2c2;"; } ?>">
								<h4><a href="<?php echo $facebook_canvas_url; ?>/user_profile.php?user=<?php echo $id; ?>"><?php echo get_name_for_id($id); ?></a><br>
								Attacked: <?php echo $created_at; ?><br>
								Status:
								<?php if($wonorlost == "won" or $wonorlost == "lost") { ?>
									<?php echo ucwords($wonorlost); ?> <?php echo $gold_change; ?> gold
								<?php } else { ?>
									<?php echo ucwords($wonorlost); ?>
								<?php } ?>
								</h4>
							</td>
						</tr>
						
	<?php
			}
		}
		
	?>
					</table>
					</center>

  					</div>
  				</div>
  			</div>
		</div>

		<div class="right-column-equal-size">
			<div class="box_head">Last 20 Pirates That've Attacked You</div>
			<div class="box-content">
				<center>
				<table cellpadding="0" cellspacing="0" border=0 style="text-align: center;">
		<?php
			$defense = $DB->GetArray("select * from battles where user_id_2 = ? and result !='p' order by created_at desc limit 20", array($profile_id));
		
			if(count($defense) == 0) {
				echo "<h1 style='padding:30px; text-align:center'>You Haven't been attacked yet. <a href='index.php'>Go Sailin'</a></h1>";
			}
			else {
				foreach($defense as $key => $value) {				
					$id = $value['user_id'];
					$score = $value['b'];
					$level = $value['level'];
					$created_at = date("n/j/y g:i a", strtotime($value['created_at']));
					$buried_coin_total = $value['buried_coin_total'];
					$rank = $key + 1;
					$result = $value['result'];
					
					$gold_change = $value['gold_change'];
					
					if($result == 'w') {
						$winorlose = 'lose';
						$wonorlost = 'lost';
					}
					else if($result == 'l') {
						$winorlose = 'win&nbsp;';
						$wonorlost = 'won';
					}
					else {	
						$winorlose = 'tie';
						$wonorlost = 'tied';
					}
					$buried_coin_total = number_format($buried_coin_total);
		?>

						<tr>
							<td style="padding: 5px 10px 0px 5px; <?php if($wonorlost == "won") { echo "background-color: #bdf9da;"; } else if($wonorlost == "lost") { echo "background-color: #f9c2c2;"; } ?>">
								<a href="<?php echo $facebook_canvas_url; ?>/user_profile.php?user=<?php echo $id; ?>">
								
								<?php echo get_square_profile_pic($id); ?>
								</a>
							</td><td style="padding: 0px 5px 0px 0px; <?php if($wonorlost == "won") { echo "background-color: #bdf9da;"; } else if($wonorlost == "lost") { echo "background-color: #f9c2c2;"; } ?>">
								<h4><a href="<?php echo $facebook_canvas_url; ?>/user_profile.php?user=<?php echo $id; ?>"><?php echo get_name_for_id($id); ?></a><br>
								Attacked: <?php echo $created_at; ?><br>
								Status:
								<?php if($wonorlost == "won" or $wonorlost == "lost") { ?>
									<?php echo ucwords($wonorlost); ?> <?php echo $gold_change; ?> gold
								<?php } else { ?>
									<?php echo ucwords($wonorlost); ?>
								<?php } ?>
								</h4>
							</td>
						</tr>
		<?php
				}
			}
		?>	
			</table>
			</center>
			</div>
		</div>

	<?php } else if($action == 'inventory') { ?>
	
		<div style="background-color: #FFFFFF; border: 1px solid rgb(204, 204, 204);">
	    <center>
	    <div style="height: 100%; width: 100%; position: relative; border: 0px solid black; margin: 5px; padding: 5px 5px 5px 5px;">
		<table>

	        <tr>
	            <td width="50" valign="center" style="padding: 10px 0px 10px 10px;">
	            	<div style='height: 50px; width: 50px; border: 1px solid gray; padding: 0px 0px 0px 0px;'>
	            		<?php image($item_gold_coins_50); ?>
	            	</div>
	           	</td>
	            <td valign="center" style="padding: 10px 0px 10px 20px; text-align:left;">
	            	<h2>Coin Assets</h2>Coins: <?php echo get_coin_total($profile_id); ?><br>
	            	Buried: <?php echo get_coin_total_buried($profile_id); ?></h5><br>
	            </td>
	            <td valign="center" style="padding: 10px 0px 10px 10px;">
	            	<?php if($user == $profile_id and get_coin_total_buried($profile_id) > 0) { ?>
	            	<form action="<?php echo "$facebook_canvas_url/retrieve_coins.php"; ?>" method="post">
	            		<input class="inputsubmit" type="Submit" value="Dig up Coins" name="use">
	            	</form>
	            	<?php } ?>
	            </td>
	        </tr>
	        
	<?php
		$booty = get_booty_reverse_order($profile_id);		

		foreach($booty as $key=>$value) {
			$stuff_id = $value['stuff_id'];	
			$booty_data = get_booty_data_from_id($stuff_id);	
			$count = $value['how_many'];
			if($count != 0) {
	?>
			<tr>
				<td width="50" valign="center" style="padding: 10px 0px 10px 10px; border-top: 1px dotted #CCC;">
					<div style="border: 1px solid gray; height: 50px; width: 50px; padding: 0px 0px 0px 0px;">
						<a href="bootyview.php?id=<?php echo $stuff_id; ?>"><?php image($booty_data[3]); ?></a>
					</div>
				</td>
				<td valign="center" style="padding: 10px 0px 10px 20px; border-top: 1px dotted #CCC; text-align:left;">
					<h2><a href="bootyview.php?id=<?php echo $stuff_id; ?>"><?php echo $booty_data[0]; ?></a> (x<?php echo $count; ?>)</h2>
				</td>
            	<td valign="center" style="padding: 10px 0px 10px 10px; border-top: 1px dotted #CCC;">
            	<?php if($stuff_id == 9 and $user == $profile_id) { ?>
            		<form action="<?php echo "$facebook_canvas_url/item_action.php?item=rum"; ?>" method="post">
            	<?php } else if($stuff_id == 12 and $user == $profile_id) { ?>
            		<form action="<?php echo "$facebook_canvas_url/item_action.php?item=ham"; ?>" method="post">
            	<?php } else { ?>
            		<form>
            	<?php } ?>
            		<input type="hidden" name="stuff_id" value="<?php echo $id; ?>">
            	<?php if(($stuff_id == 9 or $stuff_id == 12) and $user == $profile_id) { ?>
            		<input class="inputsubmit" type="Submit" value="Use Item" name="use">
            	<?php } ?>
            	&nbsp;</form></td>
			</tr>
	<?php
			}
		}
	?>
		</table>
		</div>
		</center>
		</div>
    
    <?php } else if($action == 'fellow_pirates') { ?>
 
 		<?php if($user == $profile_id) { ?>
 			<center>
 			<h5>Give your mates this link to recruit em<br>
			<?php echo $facebook_canvas_url; ?>/?i=<?php echo $user; ?></h5>
			</center><br>
 		<?php } ?>
 
 		<div class="left-column-equal-size">
			<div class="left-column-content-equal-size">
    			<div class="clearfix current-level-box">
  					<div class="box_head">
  						
  						Recruited By <?php echo get_name_for_id($profile_id); ?>
  					</div>
  					<div class="clearfix box-content" style="text-align:left;">
  						<center><h2><a href="<?php echo $facebook_canvas_url; ?>/recruit.php">Recruit More Pirates</a></h2></center>
   					  	<?php 
   					  	
   					  		$r = get_recruits($profile_id);
							//print_r($r);
							//print $profile_id;
							
							if(count($r) == 0 or $r == false) {
								echo "<br><center><div style='background-color: #FFFFFF; padding: 5px 5px 5px 5px; border: solid 1px lightgrey;'>
									<br><h1>No Pirates have been recruited by ". get_name_for_id($profile_id) . " yet!
									</h1><br><h4>Recruiting other Pirates will earn you level bonuses!<h4><br>
									</div></center>";
							} else {
								echo "<center><table style='padding: 5px; padding-top: 10px; text-align:center;' cellpadding='0' cellspacing='0' border='0'>";
								foreach($r as $key => $value) {		
									$id = $value['id'];
									$score = $value['b'];
									$level = $value['level'];
									$team = $value['team'];
									$team = ucwords($team);
									$buried_coin_total = $value['buried_coin_total'];
									$rank = $key + 1;
									echo "<tr><td style='padding: 5px 10px 0px 5px;'>";
									echo "<a href='$facebook_canvas_url/user_profile.php?user=$id'>" . get_square_profile_pic($id) . "</a>";
									echo "</td><td><h4><a href='$facebook_canvas_url/user_profile.php?user=$id'>" . get_name_for_id($id) ."</a><br>$team Pirate<br>level: $level coins: $buried_coin_total<h4>";
									echo "</td></tr>";
								}
								echo "</table></center>";
							}
						?> 					
  					</div>
  				</div>
  			</div>
		</div>

		<?php 
			$app_friends = $facebook->api_client->friends_list;
		?>

		<div class="right-column-equal-size">
			<div class="box_head">Pirate Friends of <?php echo get_name_for_id($profile_id); ?></div>
			<div class="box-content">
				<?php
				if($user == $profile_id) {
				
					if(isset($app_friends) and count($app_friends) > 0) {
				
						$friend_string = implode(",", $app_friends);
	
						$r = $memcache->get($user . ":af");
						if($r == false) {
							$sql = "select id, level, coin_total, buried_coin_total from users where id in ($friend_string);";
							$r = $DB->GetArray($sql);
		
							$memcache->set($user . ":af", $r, false, 1800);
						}
						echo "<center><table style='padding: 5px; padding-top: 10px; text-align:center;' cellpadding='0' cellspacing='0' border='0'>";
						foreach($r as $key => $value) {
							$id = $value['id'];
							$level = $value['level'];
							$buried_coin_total = $value['buried_coin_total'];
							$rank = $key + 1;
							echo "<tr><td style='padding: 5px 10px 0px 5px;'>";
							echo "<a href='$facebook_canvas_url/user_profile.php?user=$id'><fb:profile-pic size='square' uid='$id' linked='false'/></a>";
							echo "</td><td><h4><a href='$facebook_canvas_url/user_profile.php?user=$id'><fb:name uid='$id' linked='false' /></a><br>" . ucwords($team) . " Pirate<br>level: $level coins: " . number_format($buried_coin_total) . "<h4>";
							echo "</td></tr>";
						}
						echo "</table></center>";
					} else {
						echo "<center><div style='background-color: #FFFFFF; padding: 5px 5px 5px 5px; border: solid 1px lightgrey;'>
							<br><h1>None of your friends are Pirates yet.
							</h1><br><h4>Recruiting other Pirates will earn you a level bonus!<h4><br>
							<h1><a href='recruit.php'>Invite them to try Pirates</a></h1><br></div></center>";
					}
				} else {
					echo "<center><br><h1>Available to Profile Owner Only!</h1><br></center>";
				}
				?>
			</div>
		</div>
     	
	<?php } else if($action == 'discussion') { ?>
	
		<div style="background-color: #FFFFFF; border-right: 1px solid rgb(204, 204, 204); border-left: 1px solid rgb(204, 204, 204); border-bottom: 1px solid rgb(204, 204, 204);">
		<fb:comments xid="<?php echo "pirates_" . $profile_id . "_wall"; ?>" candelete="<?php echo $profile_id; ?>" returnurl="<?php echo $facebook_canvas_url; ?>/user_profile.php?action=discussion&user=<?php echo $profile_id; ?>">
   			<fb:title><fb:name uid="<?php echo $profile_id; ?>" useyou="false" ifcantsee="Anonymous User" possessive="true"/> Pirate Wall</fb:title>
 		</fb:comments>
 		</div>
 		
	<?php } ?>
	
	<div style="clear:both;"></div>
</div>

<?php require_once 'ad_bottom.inc.php'; ?>
<?php require_once 'footer.php'; ?>