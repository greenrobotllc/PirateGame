<?php

require_once 'includes.php';
if($cheaters == null) {
	$cheaters = array();
}
if(false) {
  require_once 'cheaterboard.php';
  exit;
}  

include_once 'config_network.php';
global $network_id;

if($network_id == 1) {
  $num_per_chunk = 10;
}
else {
  $num_per_chunk = 50;
}

$in = $_REQUEST['i'];
if(isset($in)) {
	$facebook->redirect("$facebook_canvas_url/install.php?i=$in");
}
//print_r($user);
$user_in_db = is_user_in_db($user);
if($user_in_db == 0) {
	$facebook->redirect("$facebook_canvas_url/install.php");
}

//get the selected tab
$action = $_REQUEST['action'];

if(!isset($action)) {
	$action = "weekly";
}

function get_time_left_weekly() {
	global $memcache, $DB;

/*	if($memcache) {
		$last_weekly_date = $memcache->get("weekly_date:");
		if(!is_bool($last_weekly_date) and $last_weekly_date != "") {
			time_left_of_1week($last_weekly_date);
			return;
		}
	}	*/
	
	$sql = "select generic_date from generic_data where unique_identifier = 'weekly_refresh_date'";
	try {
		$last_weekly_date = $DB->GetOne($sql);
	} catch (Exception $e) { return false; }

	if(is_bool($last_weekly_date)) {
		return false;
	}
	
/*	if($memcache) {
		$memcache->set("weekly_date:", $last_weekly_date, false, 3600);
	}*/
	
	//this echo's it out
	time_left_of_1week($last_weekly_date);
}

function get_data_chunk($leaderboard, $chunk_number) {
	global $memcache, $outlaws_memcache, $DB;
	
	if($memcache) {
		if($leaderboard == "overall") {
    		$person_array = $memcache->get($chunk_number . ":" . $outlaws_memcache . ":ldrovrall");
    	} else if($leaderboard == "mostcoins") {
    		$person_array = $memcache->get($chunk_number . ":" . $outlaws_memcache . ":ldrmstcoins");
    	} else if($leaderboard == "level") {
    		$person_array = $memcache->get($chunk_number . ":" . $outlaws_memcache . ":ldrhghlvl");
    	} else if($leaderboard == "buccaneer_overall") {
    		$person_array = $memcache->get($chunk_number . ":" . $outlaws_memcache . ":ldrovrall_buc");
    	} else if($leaderboard == "buccaneer_mostcoins") {
    		$person_array = $memcache->get($chunk_number . ":" . $outlaws_memcache . ":ldrmstcoins_buc");
    	} else if($leaderboard == "buccaneer_level") {
    		$person_array = $memcache->get($chunk_number . ":" . $outlaws_memcache . ":ldrhghlvl_buc");
    	} else if($leaderboard == "corsair_overall") {
    		$person_array = $memcache->get($chunk_number . ":" . $outlaws_memcache . ":ldrovrall_cor");
    	} else if($leaderboard == "corsair_mostcoins") {
    		$person_array = $memcache->get($chunk_number . ":" . $outlaws_memcache . ":ldrmstcoins_cor");
    	} else if($leaderboard == "corsair_level") {
    		$person_array = $memcache->get($chunk_number . ":" . $outlaws_memcache . ":ldrhghlvl_cor");
    	} else if($leaderboard == "barbary_overall") {
    		$person_array = $memcache->get($chunk_number . ":" . $outlaws_memcache . ":ldrovrall_bar");
    	} else if($leaderboard == "barbary_mostcoins") {
    		$person_array = $memcache->get($chunk_number . ":" . $outlaws_memcache . ":ldrmstcoins_bar");
    	} else if($leaderboard == "barbary_level") {
    		$person_array = $memcache->get($chunk_number . ":" . $outlaws_memcache . ":ldrhghlvl_bar");
    	} else if($leaderboard == "weekly_miles") {
    		$person_array = $memcache->get($chunk_number . ":" . $outlaws_memcache . ":ldrweeklymiles");
    	} else if($leaderboard == "weekly_money") {
    		$person_array = $memcache->get($chunk_number . ":" . $outlaws_memcache . ":ldrweeklymoney");
    	} else if($leaderboard == "weekly_money") {
    		$person_array = $memcache->get($chunk_number . ":" . $outlaws_memcache . ":ldrweeklylevel");
    	}
    	    
		if($person_array != false and $person_array != "") {
			return unserialize($person_array[0]['array_data']);
		}
  	}
	
	if($leaderboard == "overall") {
		$sql = "select array_data from leader_overall where uid = ?";
	} else if($leaderboard == "mostcoins") {
		$sql = "select array_data from leader_most_coins where uid = ?";
	} else if($leaderboard == "level") {
		$sql = "select array_data from leader_highest_level where uid = ?";
	} else if($leaderboard == "buccaneer_overall") {
		$sql = "select array_data from leader_buccaneer_overall where uid = ?";
	} else if($leaderboard == "buccaneer_mostcoins") {
		$sql = "select array_data from leader_buccaneer_most_coins where uid = ?";
	} else if($leaderboard == "buccaneer_level") {
		$sql = "select array_data from leader_buccaneer_highest_level where uid = ?";
	} else if($leaderboard == "corsair_overall") {
		$sql = "select array_data from leader_corsair_overall where uid = ?";
	} else if($leaderboard == "corsair_mostcoins") {
		$sql = "select array_data from leader_corsair_most_coins where uid = ?";
	} else if($leaderboard == "corsair_level") {
		$sql = "select array_data from leader_corsair_highest_level where uid = ?";
	} else if($leaderboard == "barbary_overall") {
		$sql = "select array_data from leader_barbary_overall where uid = ?";
	} else if($leaderboard == "barbary_mostcoins") {
		$sql = "select array_data from leader_barbary_most_coins where uid = ?";
	} else if($leaderboard == "barbary_level") {
		$sql = "select array_data from leader_barbary_highest_level where uid = ?";
	} else if($leaderboard == "weekly_miles") {
		$sql = "select array_data from leader_weekly_miles where uid = ?";
	} else if($leaderboard == "weekly_money") {
		$sql = "select array_data from leader_weekly_money where uid = ?";
	} else if($leaderboard == "weekly_level") {
		$sql = "select array_data from leader_weekly_level where uid = ?";
	}
	
	try {
		$person_array = $DB->GetArray($sql, array($chunk_number + 1));
	} catch (Exception $e) { return false; }
	if($person_array == false or count($person_array) == 0) {
		return false;
	}
	
	if($memcache) {
		if($leaderboard == "overall") {
    		$memcache->set($chunk_number . ":" . $outlaws_memcache . ":ldrovrall", $person_array);
    	} else if($leaderboard == "mostcoins") {
    		$memcache->set($chunk_number . ":" . $outlaws_memcache . ":ldrmstcoins", $person_array);
    	} else if($leaderboard == "level") {
    		$memcache->set($chunk_number . ":" . $outlaws_memcache . ":ldrhghlvl", $person_array);
    	} else if($leaderboard == "buccaneer_overall") {
    		$memcache->set($chunk_number . ":" . $outlaws_memcache . ":ldrovrall_buc", $person_array);
    	} else if($leaderboard == "buccaneer_mostcoins") {
    		$memcache->set($chunk_number . ":" . $outlaws_memcache . ":ldrmstcoins_buc", $person_array);
    	} else if($leaderboard == "buccaneer_level") {
    		$memcache->set($chunk_number . ":" . $outlaws_memcache . ":ldrhghlvl_buc", $person_array);
    	} else if($leaderboard == "corsair_overall") {
    		$memcache->set($chunk_number . ":" . $outlaws_memcache . ":ldrovrall_cor", $person_array);
    	} else if($leaderboard == "corsair_mostcoins") {
    		$memcache->set($chunk_number . ":" . $outlaws_memcache . ":ldrmstcoins_cor", $person_array);
    	} else if($leaderboard == "corsair_level") {
    		$memcache->set($chunk_number . ":" . $outlaws_memcache . ":ldrhghlvl_cor", $person_array);
    	} else if($leaderboard == "barbary_overall") {
    		$memcache->set($chunk_number . ":" . $outlaws_memcache . ":ldrovrall_bar", $person_array);
    	} else if($leaderboard == "barbary_mostcoins") {
    		$memcache->set($chunk_number . ":" . $outlaws_memcache . ":ldrmstcoins_bar", $person_array);
    	} else if($leaderboard == "barbary_level") {
    		$memcache->set($chunk_number . ":" . $outlaws_memcache . ":ldrhghlvl_bar", $person_array);
    	} else if($leaderboard == "weekly_miles") {
    		$memcache->set($chunk_number . ":" . $outlaws_memcache . ":ldrweeklymiles", $person_array);
    	} else if($leaderboard == "weekly_money") {
    		$memcache->set($chunk_number . ":" . $outlaws_memcache . ":ldrweeklymoney", $person_array);
    	} else if($leaderboard == "weekly_level") {
    		$memcache->set($chunk_number . ":" . $outlaws_memcache . ":ldrweeklylevel", $person_array);
    	}
    }
	
	return unserialize($person_array[0]['array_data']);
}

function print_top_person($user_id, $rank, $leaderboard) {
	global $facebook_canvas_url, $user;

	if($rank == 1) {
		$rank_color = 'red';
	} else if($rank == 2) {
		$rank_color = 'blue';
	} else if($rank == 3) {
		$rank_color = 'green';
	} else {
		$rank_color = 'black';
	}
?>

	<?php 
	if($user_id == $user) {
		$background = '#ffffcc';
	}
	else {
		$background = '#FFFFFF';
	}
	?>
	
	<td style='text-align: center;' colspan="5">
		<table cellpadding="0" cellspacing="0" border="0" style='background-color:<?php echo $background; ?>' >
			<tr>
				<td width="100" style="text-align: center; padding: 0px 5px 0px 0px;">
					<?php if($rank > 999) { ?>
						<h1 style="font-size: 200%; color: <?php echo $rank_color; ?>;"><?php echo $rank; ?></h1>
					<?php } else if($rank > 99) { ?>
						<h1 style="font-size: 300%; color: <?php echo $rank_color; ?>;"><?php echo $rank; ?></h1>
					<?php } else { ?>
						<h1 style="font-size: 400%; color: <?php echo $rank_color; ?>;"><?php echo $rank; ?></h1>
					<?php } ?>
				</td><td>
				<a <?php href('user_profile.php', "?user=$user_id"); ?>>
					<?php echo get_square_profile_pic($user_id); ?>
				</td><td  style="padding: 0px 0px 0px 5px;" valign="top">
					<table cellpadding="0" cellspacing="0" border="0">
						<tr>
							<td width="150" style="padding: 0px 5px 0px 0px;">
							<h1><a href='user_profile.php?user=<?php echo $user_id; ?>'><?php echo get_name_for_id($user_id); ?></a></h1>
							<?php if($leaderboard == "overall" or $leaderboard == "buccaneer_overall" or $leaderboard == "corsair_overall" or $leaderboard == "barbary_overall") { ?>
								<h2>Level: <font color="#3B5998"><?php echo get_level($user_id); ?></font><br>
								Gold: <font color="#3B5998"><?php echo number_format(get_coin_total_buried($user_id)); ?></font></h2></td>
							<?php } else if($leaderboard == "mostcoins" or $leaderboard == "buccaneer_mostcoins" or $leaderboard == "corsair_mostcoins" or $leaderboard == "barbary_mostcoins") { ?>
								<h2>Level: <?php echo get_level($user_id); ?><br>
								Gold: <font color="#3B5998"><?php echo number_format(get_coin_total_buried($user_id)); ?></font></h2></td>								
							<?php } else if ($leaderboard == "level" or $leaderboard == "buccaneer_level" or $leaderboard == "corsair_level" or $leaderboard == "barbary_level") { ?>
								<h2>Level: <font color="#3B5998"><?php echo get_level($user_id); ?></font><br>
								Gold: <?php echo number_format(get_coin_total_buried($user_id)); ?></h2></td>									
							<?php } else if ($leaderboard == "weekly_miles") { ?>
								<h2>Level: <?php echo get_level($user_id); ?><br>
								Miles: <font color="#3B5998"><?php echo number_format(get_weekly_miles_traveled($user_id)); ?></font></h2></td>								
							<?php } else if($leaderboard == "weekly_money") { ?>
								<h2>Level: <?php echo get_level($user_id); ?><br>
								Coins: <font color="#3B5998"><?php echo number_format((get_coin_total_buried($user_id) + get_coin_total($user_id)) - get_weekly_money($user_id)); ?></font></h2></td>								
							<?php } else if($leaderboard == "weekly_level") { ?>
								<h2>Level: <?php echo get_level($user_id); ?><br>
								Gained: <font color="#3B5998"><?php echo number_format(get_level($user_id) - get_weekly_level($user_id)); ?></font></h2></td>								
							<?php } ?>
							<td width="100" style="text-align: center; padding: 0px 5px 0px 0px;"><h4>Pirate Since<br><?php echo get_date_joined($user_id); ?></h4></td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
	</td>
<?php
}

/*function get_leaderboard_user_postion($user, $leaderboard) {
	global $memcache, $outlaws_memcache, $DB;
	
	//we only store this in memcache
	if($memcache) {
		if($leaderboard == 'notoriety') {
			$chunk_position = $memcache->get($user . ":" . $outlaws_memcache . ":ldrnotpos");
		} else if($leaderboard == 'richest') {
			$chunk_position = $memcache->get($user . ":" . $outlaws_memcache . ":ldrrichpos");
		}
		if($chunk_position === false or $chunk_position == "" or $chunk_position == "NULL" or $chunk_position === NULL) {
			return false;
		} else {
			//can return 0 so watch our for testing for false
			return $chunk_position;
		}
	} else {
		return false;
	}
}*/

$leaderboard_value = $_REQUEST['value'];
if(!isset($leaderboard_value) or $leaderboard_value == "") {
	if($action == "weekly") {
		$leaderboard_value = "weekly_miles";
	} else if($action == "overall") {
		$leaderboard_value = "overall";
	}
}
$screen_number = $_REQUEST['page_num'];
if(!isset($screen_number)) {
	$screen_number = 0;
}

print dashboard();
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

<div style="padding: 15px 0px 0px 0px;">
	<center><h1>Leaderboards</h1>
	<h4>Are you on top?</h4>
	<small><font color="grey">real-time stats<br>rank refreshed hourly</font></small></center>
</div>

<?php if($network_id == 1) { ?>
 <div class="tabArea">
  <a href="<?php echo $facebook_canvas_url; ?>/leaderboard.php?action=weekly" class="tab <?php if($action == 'weekly' || $action == ''){ echo 'activeTab'; } ?>">Weekly</a>
  <a href="<?php echo $facebook_canvas_url; ?>/leaderboard.php?action=overall" class="tab <?php if($action == 'overall'){ echo 'activeTab'; } ?>">Overall</a>
  <a href="<?php echo $facebook_canvas_url; ?>/leaderboard.php?action=winners" class="tab <?php if($action == 'winners'){ echo 'activeTab'; } ?> ">Weekly Winners</a>
</div>

<?php } else { ?>
<fb:tabs>
	<fb:tab_item href="<?php echo $facebook_canvas_url; ?>/leaderboard.php?action=weekly" title="Weekly" <?php if($action == "weekly" or $action == "") { echo 'selected="true"'; } ?>/>
	<fb:tab_item href="<?php echo $facebook_canvas_url; ?>/leaderboard.php?action=overall" title="Overall" <?php if($action == "overall") { echo 'selected="true"'; } ?>/>
	<fb:tab_item href="<?php echo $facebook_canvas_url; ?>/leaderboard.php?action=winners" title="Weekly Winners" align="right" <?php if($action == "winners") { echo 'selected="true"'; } ?>/>
</fb:tabs>

<?php } ?>



<div style="padding: 10px; background-color: #f7f7f7;">

	<?php if($action != "winners") { ?>
		<table cellpadding="0" cellspacing="0" border="0" width="100%">
			<tr>
				<td width="150" valign="top" rowspan="2" style="padding: 0px 10px 0px 0px;">
					<div style="background-color: #FFFFFF; border: 1px solid gray; padding: 5px 5px 5px 5px;">
					<ul style="margin: 0pt; padding-left: 5px; list-style-type: none;">
						
					<?php if($action == "weekly") { ?>
					
						<li style="background-color: #ffffcc;"><center><h4>All</h4></center></li>
						<li style="padding: 2px 0px 0px 0px;"></li>
						<li <?php if($leaderboard_value == 'weekly_miles') { echo 'style="background-color: #3B5998; padding: 3px 3px 3px 3px;"'; } ?>><a href="<?php echo $facebook_canvas_url; ?>/leaderboard.php?value=weekly_miles&action=weekly" <?php if($leaderboard_value == 'weekly_miles') { echo 'style="color: #FFFFFF; font-weight: bold;"'; } ?>>Miles Travelled</a></li>
						<li <?php if($leaderboard_value == 'weekly_money') { echo 'style="background-color: #3B5998; padding: 3px 3px 3px 3px;"'; } ?>><a href="<?php echo $facebook_canvas_url; ?>/leaderboard.php?value=weekly_money&action=weekly" <?php if($leaderboard_value == 'weekly_money') { echo 'style="color: #FFFFFF; font-weight: bold;"'; } ?>>Coins Gained</a></li>
						<li <?php if($leaderboard_value == 'weekly_level') { echo 'style="background-color: #3B5998; padding: 3px 3px 3px 3px;"'; } ?>><a href="<?php echo $facebook_canvas_url; ?>/leaderboard.php?value=weekly_level&action=weekly" <?php if($leaderboard_value == 'weekly_level') { echo 'style="color: #FFFFFF; font-weight: bold;"'; } ?>>Levels Gained</a></li>
						<li style="padding: 2px 0px 0px 0px;"></li>		
					
					<?php } else if($action == "overall") { ?>
					
						<li style="background-color: #ffffcc;"><center><h4>All</h4></center></li>
						<li style="padding: 2px 0px 0px 0px;"></li>
						<li <?php if($leaderboard_value == 'overall') { echo 'style="background-color: #3B5998; padding: 3px 3px 3px 3px;"'; } ?>><a href="<?php echo $facebook_canvas_url; ?>/leaderboard.php?value=overall&action=overall" <?php if($leaderboard_value == 'overall') { echo 'style="color: #FFFFFF; font-weight: bold;"'; } ?>>Overall</a></li>					
						<li <?php if($leaderboard_value == "mostcoins") { echo 'style="background-color: #3B5998; padding: 3px 3px 3px 3px;"'; } ?>><a href="<?php echo $facebook_canvas_url; ?>/leaderboard.php?value=mostcoins&action=overall" <?php if($leaderboard_value == "mostcoins") { echo 'style="color: #FFFFFF; font-weight: bold;"'; } ?>>Most Coins</a></li>
						<li <?php if($leaderboard_value == "level") { echo 'style="background-color: #3B5998; padding: 3px 3px 3px 3px;"'; } ?>><a href="<?php echo $facebook_canvas_url; ?>/leaderboard.php?value=level&action=overall" <?php if($leaderboard_value == "level") { echo 'style="color: #FFFFFF; font-weight: bold;"'; } ?>>Highest Level</a></li>
						<li style="padding: 2px 0px 0px 0px;"></li>

						<li style="background-color: #ffffcc;"><center><h4>Top Buccaneer</h4></center></li>
						<li style="padding: 2px 0px 0px 0px;"></li>
						<li <?php if($leaderboard_value == 'buccaneer_overall') { echo 'style="background-color: #3B5998; padding: 3px 3px 3px 3px;"'; } ?>><a href="<?php echo $facebook_canvas_url; ?>/leaderboard.php?value=buccaneer_overall&action=overall" <?php if($leaderboard_value == 'buccaneer_overall') { echo 'style="color: #FFFFFF; font-weight: bold;"'; } ?>>Overall</a></li>					
						<li <?php if($leaderboard_value == "buccaneer_mostcoins") { echo 'style="background-color: #3B5998; padding: 3px 3px 3px 3px;"'; } ?>><a href="<?php echo $facebook_canvas_url; ?>/leaderboard.php?value=buccaneer_mostcoins&action=overall" <?php if($leaderboard_value == "buccaneer_mostcoins") { echo 'style="color: #FFFFFF; font-weight: bold;"'; } ?>>Most Coins</a></li>
						<li <?php if($leaderboard_value == "buccaneer_level") { echo 'style="background-color: #3B5998; padding: 3px 3px 3px 3px;"'; } ?>><a href="<?php echo $facebook_canvas_url; ?>/leaderboard.php?value=buccaneer_level&action=overall" <?php if($leaderboard_value == "buccaneer_level") { echo 'style="color: #FFFFFF; font-weight: bold;"'; } ?>>Highest Level</a></li>
						<li style="padding: 2px 0px 0px 0px;"></li>

						<li style="background-color: #ffffcc;"><center><h4>Top Corsair</h4></center></li>
						<li style="padding: 2px 0px 0px 0px;"></li>
						<li <?php if($leaderboard_value == 'corsair_overall') { echo 'style="background-color: #3B5998; padding: 3px 3px 3px 3px;"'; } ?>><a href="<?php echo $facebook_canvas_url; ?>/leaderboard.php?value=corsair_overall&action=overall" <?php if($leaderboard_value == 'corsair_overall') { echo 'style="color: #FFFFFF; font-weight: bold;"'; } ?>>Overall</a></li>					
						<li <?php if($leaderboard_value == "corsair_mostcoins") { echo 'style="background-color: #3B5998; padding: 3px 3px 3px 3px;"'; } ?>><a href="<?php echo $facebook_canvas_url; ?>/leaderboard.php?value=corsair_mostcoins&action=overall" <?php if($leaderboard_value == "corsair_mostcoins") { echo 'style="color: #FFFFFF; font-weight: bold;"'; } ?>>Most Coins</a></li>
						<li <?php if($leaderboard_value == "corsair_level") { echo 'style="background-color: #3B5998; padding: 3px 3px 3px 3px;"'; } ?>><a href="<?php echo $facebook_canvas_url; ?>/leaderboard.php?value=corsair_level&action=overall" <?php if($leaderboard_value == "corsair_level") { echo 'style="color: #FFFFFF; font-weight: bold;"'; } ?>>Highest Level</a></li>
						<li style="padding: 2px 0px 0px 0px;"></li>

						<li style="background-color: #ffffcc;"><center><h4>Top Barbary</h4></center></li>
						<li style="padding: 2px 0px 0px 0px;"></li>
						<li <?php if($leaderboard_value == 'barbary_overall') { echo 'style="background-color: #3B5998; padding: 3px 3px 3px 3px;"'; } ?>><a href="<?php echo $facebook_canvas_url; ?>/leaderboard.php?value=barbary_overall&action=overall" <?php if($leaderboard_value == 'barbary_overall') { echo 'style="color: #FFFFFF; font-weight: bold;"'; } ?>>Overall</a></li>					
						<li <?php if($leaderboard_value == "barbary_mostcoins") { echo 'style="background-color: #3B5998; padding: 3px 3px 3px 3px;"'; } ?>><a href="<?php echo $facebook_canvas_url; ?>/leaderboard.php?value=barbary_mostcoins&action=overall" <?php if($leaderboard_value == "barbary_mostcoins") { echo 'style="color: #FFFFFF; font-weight: bold;"'; } ?>>Most Coins</a></li>
						<li <?php if($leaderboard_value == "barbary_level") { echo 'style="background-color: #3B5998; padding: 3px 3px 3px 3px;"'; } ?>><a href="<?php echo $facebook_canvas_url; ?>/leaderboard.php?value=barbary_level&action=overall" <?php if($leaderboard_value == "barbary_level") { echo 'style="color: #FFFFFF; font-weight: bold;"'; } ?>>Highest Level</a></li>
						<li style="padding: 2px 0px 0px 0px;"></li>
						
					<?php } ?>
					
					</ul>
					</div><br>
					<center>
						<?php
							print adsense_120($user);
							//echo "<br><br><br>";
							//print rmx_120($user);
						?>
					</center>
				</td>
				<td width="460" valign="top" style="padding: 0px 0px 3px 0px; text-align: right;">
						<?php 	if(!isset($leaderboard_value) or $leaderboard_value == 'overall' or $leaderboard_value == "") {
									$data_array = get_data_chunk('overall', $screen_number);
									$leaderboard_found = true;
								} else if($leaderboard_value == 'mostcoins') {
									$data_array = get_data_chunk('mostcoins', $screen_number);
									$leaderboard_found = true;								
								} else if($leaderboard_value == 'level') {
									$data_array = get_data_chunk('level', $screen_number);
									$leaderboard_found = true;
								} else if($leaderboard_value == 'buccaneer_overall') {
									$data_array = get_data_chunk('buccaneer_overall', $screen_number);
									$leaderboard_found = true;
								} else if($leaderboard_value == 'buccaneer_mostcoins') {
									$data_array = get_data_chunk('buccaneer_mostcoins', $screen_number);
									$leaderboard_found = true;	
								} else if($leaderboard_value == 'buccaneer_level') {
									$data_array = get_data_chunk('buccaneer_level', $screen_number);
									$leaderboard_found = true;
								} else if($leaderboard_value == 'corsair_overall') {
									$data_array = get_data_chunk('corsair_overall', $screen_number);
									$leaderboard_found = true;
								} else if($leaderboard_value == 'corsair_mostcoins') {
									$data_array = get_data_chunk('corsair_mostcoins', $screen_number);
									$leaderboard_found = true;	
								} else if($leaderboard_value == 'corsair_level') {
									$data_array = get_data_chunk('corsair_level', $screen_number);
									$leaderboard_found = true;
								} else if($leaderboard_value == 'barbary_overall') {
									$data_array = get_data_chunk('barbary_overall', $screen_number);
									$leaderboard_found = true;
								} else if($leaderboard_value == 'barbary_mostcoins') {
									$data_array = get_data_chunk('barbary_mostcoins', $screen_number);
									$leaderboard_found = true;	
								} else if($leaderboard_value == 'barbary_level') {
									$data_array = get_data_chunk('barbary_level', $screen_number);
									$leaderboard_found = true;
								} else if($leaderboard_value == 'weekly_miles') {
									$data_array = get_data_chunk('weekly_miles', $screen_number);
									$leaderboard_found = true;
								} else if($leaderboard_value == 'weekly_money') {
									$data_array = get_data_chunk('weekly_money', $screen_number);
									$leaderboard_found = true;
								} else if($leaderboard_value == 'weekly_level') {
									$data_array = get_data_chunk('weekly_level', $screen_number);
									$leaderboard_found = true;									
								}
							
								echo "<table border='0' cellpadding='0' cellspacing='0' width='100%'><tr>";
								/*$leaderboard_position = get_leaderboard_user_postion($user, $leaderboard_value);

								if(!is_bool($leaderboard_position)) {
									echo "<td style='text-align: left;'><a href=\"leaderboard.php?value=$leaderboard_value&page_num=$leaderboard_position\">You are on page " . ($leaderboard_position + 1) . " of this leaderboard!</a></td>";
								}*/
								if($action == "weekly") {
									echo "<td style='text-align: left;'>Week Countdown: <font color='blue'>";
									get_time_left_weekly();
									echo "</font></td>";
								}
							
								echo "<td style='text-align: right;'>";
								if($screen_number != 0) {
									echo "<a href=\"leaderboard.php?value=$leaderboard_value&action=$action&page_num=" . ($screen_number - 1) . "\"><- Previous </a> | ";
								}
								
								if($leaderboard_found) {
									echo "<a href=\"leaderboard.php?value=$leaderboard_value&action=$action&page_num=" . ($screen_number + 1) . "\">Next -></a>";
								}
								echo "</td></tr></table>";
						?>
					
					<div style="background-color: #FFFFFF; border: 1px solid gray; padding: 10px 10px 10px 10px;">
						<?php 
								if($data_array == false or count($data_array) == 0) {
									echo "<center><h1>Leaderboard entry not found!</h1><br><h5>The leaderboard may be recalculating.<br>Check back in 5 minutes!</h5></center>";
									$leaderboard_found = false;
								}
								if($leaderboard_found) {
									echo "<table cellpadding='0' cellspacing='0' border='0' width='100%'>";
									for($i = 0; $i < count($data_array); $i++) {
										echo "<tr>";
										if($screen_number == 0 and $i == 0) {
											print_top_person($data_array[$i]['id'], 1, $leaderboard_value);
										} else if($screen_number == 0 and $i == 1) {
											print_top_person($data_array[$i]['id'], 2, $leaderboard_value);									
										} else if($screen_number == 0 and $i == 2) {
											print_top_person($data_array[$i]['id'], 3, $leaderboard_value);	
										} else {
											print_top_person($data_array[$i+ ($num_per_chunk * $screen_number)]['id'], $i + ($num_per_chunk * $screen_number) + 1, $leaderboard_value);
										}
										echo "</tr><tr><td colspan='5' style='padding: 2px 0px 2px 0px;'><div style='background-color: lightgrey; width: 100%; height: 1px;'></div></td></tr>";
									}
									echo "</table>";
								}
						?>
					</div>
					
					<?php
						if($screen_number != 0) {
							echo "<a href=\"leaderboard.php?value=$leaderboard_value&page_num=" . ($screen_number - 1) . "\"><- Previous </a> | ";
						}
						
						if($leaderboard_found) {
							echo "<a href=\"leaderboard.php?value=$leaderboard_value&page_num=" . ($screen_number + 1) . "\">Next -></a>";
						}					
					?>
					
				</td>
			</tr>
		</table>
	<?php
		$moderators = get_moderators();
		$banned = get_banned();

		if (in_array($user, $moderators )) {
			$candelete = 'true';
		} else {
			$candelete = 'false';
		}

		if (in_array($user, $banned )) {
			$canpost = 'false';
		} else {
			$canpost = 'true';
		}
	?>


<?php
global $network_id;
 if($network_id == 0) { ?>

	<br><br>
	<fb:comments showform="true" xid="leaderboard_comments" canpost="<?php echo $canpost; ?>" candelete="<?php echo $candelete; ?>" returnurl="<?php echo $facebook_canvas_url; ?>/leaderboard.php">
		<fb:title>Pirate Leaderboard</fb:title>
	</fb:comments>

<?php } ?>

	<?php } else { ?>
		<center><h2 style="padding: 0px 0px 5px 0px">Last Weeks Winners</h2></center>
		<div class="full-column">
			<div class="box_head">First Place Winners</div>
    		<div class="box-content">
    			<center>
    			<table>
    				<tr>
    					<td width="200">
    						<table><tr>
    							<?php $id_array = get_last_weekly_winners("miles_gold"); ?>
    							<td>
									<a href='user_profile.php?user=<?php echo $id_array[0]['uid']; ?>'><fb:profile-pic uid=<?php echo $id_array[0]['uid']; ?> size='square' linked='false' /></a>
									
								</td><td  style="padding: 0px 5px 0px 5px;" valign="top">
									<h1><a href='user_profile.php?user=<?php echo $user_id; ?>'><fb:name uid="<?php echo $id_array[0]['uid']; ?>" useyou='false' linked='false' ifcantsee='Anonymous User'/></a></h1>
									<h4>First Place<br><font color="red">Miles Travelled</font></h4>
								</td>
							</tr></table>
    					</td><td width="200">
    						<table><tr>
    							<?php $id_array = get_last_weekly_winners("money_gold"); ?>
    							<td>
									<a href='user_profile.php?user=<?php echo $id_array[0]['uid']; ?>'><fb:profile-pic uid=<?php echo $id_array[0]['uid']; ?> size='square' linked='false' />
									</a>
								</td><td  style="padding: 0px 5px 0px 5px;" valign="top">
									<h1>
									<a href='user_profile.php?user=<?php echo $id_array[0]['uid']; ?>'>
									<fb:name uid="<?php echo $id_array[0]['uid']; ?>" useyou='false' linked='false' ifcantsee='Anonymous User'/>
									</a>
									</h1>
									<h4>First Place<br><font color="red">Coins Gained</font></h4>
								</td>
							</tr></table>
    					</td><td width="200">
    						<table><tr>
    							<?php $id_array = get_last_weekly_winners("level_gold"); ?>
    							<td><a href='user_profile.php?user=<?php echo $id_array[0]['uid']; ?>'>
									<fb:profile-pic uid=<?php echo $id_array[0]['uid']; ?> size='square' linked='false' /></a>
								</td><td  style="padding: 0px 5px 0px 5px;" valign="top">
									<h1>
									<a href='user_profile.php?user=<?php echo $id_array[0]['uid']; ?>'>
									<fb:name uid="<?php echo $id_array[0]['uid']; ?>" useyou='false' linked='false' ifcantsee='Anonymous User'/></a></h1>
									<h4>First Place<br><font color="red">Levels Gained</font></h4>
								</td>
							</tr></table>
    					</td>
    				</tr>
    			</table>
    			</center>
    		</div>
    	</div>
		<br>
		<div class="full-column">
			<div class="box_head">Second Place Winners</div>
    		<div class="box-content">
    			<center>
    			<table>
    				<tr>
    					<td width="200">
    						<table><tr>
    							<?php $id_array = get_last_weekly_winners("miles_silver"); ?>
    							<td>
									<a href='user_profile.php?user=<?php echo $id_array[0]['uid']; ?>'>
									<fb:profile-pic uid=<?php echo $id_array[0]['uid']; ?> size='square' linked='false' />	</a>
									
								</td><td  style="padding: 0px 5px 0px 5px;" valign="top">
									<h1>
									<a href='user_profile.php?user=<?php echo $id_array[0]['uid']; ?>'>
									<fb:name uid="<?php echo $id_array[0]['uid']; ?>" useyou='false' linked='false' ifcantsee='Anonymous User'/>
									</a>
									</h1>
									<h4>Second Place<br><font color="blue">Miles Travelled</font></h4>
								</td>
							</tr></table>
    					</td><td width="200">
    						<table><tr>
    							<?php $id_array = get_last_weekly_winners("money_silver"); ?>
    							<td>
    							<a href='user_profile.php?user=<?php echo $id_array[0]['uid']; ?>'>
									<fb:profile-pic uid=<?php echo $id_array[0]['uid']; ?> size='square' linked='false' /></a>
								</td><td  style="padding: 0px 5px 0px 5px;" valign="top">
									<h1>
									<a href='user_profile.php?user=<?php echo $id_array[0]['uid']; ?>'>
									<fb:name uid="<?php echo $id_array[0]['uid']; ?>" useyou='false' linked='false' ifcantsee='Anonymous User'/>
									</a>
									</h1>
									<h4>Second Place<br><font color="blue">Coins Gained</font></h4>
								</td>
							</tr></table>
    					</td><td width="200">
    						<table><tr>
    							<?php $id_array = get_last_weekly_winners("level_silver"); ?>
    							<td>
    							<a href='user_profile.php?user=<?php echo $id_array[0]['uid']; ?>'><fb:profile-pic uid=<?php echo $id_array[0]['uid']; ?> size='square' linked='false' /></a>
									
								</td><td  style="padding: 0px 5px 0px 5px;" valign="top">
									<h1>
									<a href='user_profile.php?user=<?php echo $id_array[0]['uid']; ?>'>
									<fb:name uid="<?php echo $id_array[0]['uid']; ?>" useyou='false' linked='false' ifcantsee='Anonymous User'/></a></h1>
									<h4>Second Place<br><font color="blue">Levels Gained</font></h4>
								</td>
							</tr></table>
    					</td>
    				</tr>
    			</table>
    			</center>
    		</div>
    	</div>
		<br>
		<div class="full-column">
			<div class="box_head">Third Place Winners</div>
    		<div class="box-content">
    			<center>
    			<table>
    				<tr>
    					<td width="200">
    						<table><tr>
    							<?php $id_array = get_last_weekly_winners("miles_bronze"); ?>
    							<td>
									<a href='user_profile.php?user=<?php echo $id_array[0]['uid']; ?>'>	<fb:profile-pic uid=<?php echo $id_array[0]['uid']; ?> size='square' linked='false' /></a>
								</td><td  style="padding: 0px 5px 0px 5px;" valign="top">
									<h1>
									<a href='user_profile.php?user=<?php echo $id_array[0]['uid']; ?>'>
									<fb:name uid="<?php echo $id_array[0]['uid']; ?>" useyou='false' linked='false' ifcantsee='Anonymous User'/>
									</a></h1>
									<h4>Third Place<br><font color="green">Miles Travelled</font></h4>
								</td>
							</tr></table>
    					</td><td width="200">
    						<table><tr>
    							<?php $id_array = get_last_weekly_winners("money_bronze"); ?>
    							<td>
    							<a href='user_profile.php?user=<?php echo $id_array[0]['uid']; ?>'>
									<fb:profile-pic uid=<?php echo $id_array[0]['uid']; ?> size='square' linked='false' /></a>
								</td><td  style="padding: 0px 5px 0px 5px;" valign="top">
									<h1>
									<a href='user_profile.php?user=<?php echo $id_array[0]['uid']; ?>'><fb:name uid="<?php echo $id_array[0]['uid']; ?>" useyou='false' linked='false' ifcantsee='Anonymous User'/></a></h1>
									<h4>Third Place<br><font color="green">Coins Gained</font></h4>
								</td>
							</tr></table>
    					</td><td width="200">
    						<table><tr>
    							<?php $id_array = get_last_weekly_winners("level_bronze"); ?>
    							<td>
									<a href='user_profile.php?user=<?php echo $id_array[0]['uid']; ?>'>
									<fb:profile-pic uid=<?php echo $id_array[0]['uid']; ?> size='square' linked='false' /></a>
									
								</td><td  style="padding: 0px 5px 0px 5px;" valign="top">
									<h1><a href='user_profile.php?user=<?php echo $id_array[0]['uid']; ?>'><fb:name uid="<?php echo $id_array[0]['uid']; ?>" useyou='false' linked='false' ifcantsee='Anonymous User'/></a></h1>
									<h4>Third Place<br><font color="green">Levels Gained</font></h4>
								</td>
							</tr></table>
    					</td>
    				</tr>
    			</table>
    			</center>
    		</div>
    	</div>
    	<br>
	<?php } ?>

</div>

<?php require_once 'ad_bottom.inc.php'; ?>
<?php require_once 'footer.php'; ?>
