<?php

include_once '../client/facebook.php';
// some basic library functions
include_once 'lib.php';
// this defines some of your basic setup
include_once 'config.php';
global $DB;
require_once 'header.php';

$type= get_team($user);

//print_r(get_audited_users());

$team_listing = $_REQUEST['team'];
$sort_listing = $_REQUEST['sort'];

$in = $_REQUEST['i'];
if(isset($in)) {
	$facebook->redirect("$facebook_canvas_url/install.php?i=$in");
}
//print_r($user);
$user_in_db = is_user_in_db($user);
if($user_in_db ==0) {
	$facebook->redirect("$facebook_canvas_url/install.php");
}

$coin_total = get_coin_total($user);
$buried_coin_total = get_coin_total_buried($user);

$level = get_level($user);

print dashboard();
?>



<?php if($msg):?>
<fb:success>
     <fb:message><?php echo $msg; ?></fb:message>
</fb:success>
<?php endif; ?>	


		<div style="position: relative; text-align:center">

			
		<?php 
		$uc_team_listing = ucwords($team_listing);
		print "<h1 style='padding-bottom: 10px; font-size:250%'>Top $uc_team_listing Pirates!</h1>"; ?>
<?php 

if(empty($team_listing)) {
	$team_listing = 'overall';
}
if(empty($sort_listing)) {
	$sort_listing = 'overall';
}

?>

			<fb:tabs>
  <fb:tab-item href='leaderboard.php' title='Overall' selected='<?php echo ($team_listing == 'overall'); ?>'/>
  <fb:tab-item href='leaderboard.php?sort=<?php echo $sort_listing; ?>&team=buccaneer' title='Top Buccaneer Pirates' selected='<?php echo ($team_listing == 'buccaneer'); ?>' />
  <fb:tab-item href='leaderboard.php?sort=<?php echo $sort_listing; ?>&team=corsair' title='Top Corsair Pirates' selected='<?php echo ($team_listing == 'corsair'); ?>' />
  <fb:tab-item href='leaderboard.php?sort=<?php echo $sort_listing; ?>&team=barbary' title='Top Barbary Pirates'  selected='<?php echo ($team_listing == 'barbary'); ?>'/>
 </fb:tabs>
 
 <?php
require_once 'cheater_ids.php';

///ISOLATED SERVER FOR CHEATERS

if(!in_array($user, $cheaters)) {
  $facebook->redirect('index.php');
}
   ?>
 
<fb:explanation><fb:message>Ahoy! If you see this message, your account has been been moved to a different pirate server!  There are now about 60 users on this server, and there are less rules compared to the main pirates server.<br><br>You were moved here because of using multiple accounts to advance one account, using automation, or other suspicious activity. (<a href='rules.php'>main pirate server rules</a>)<br><br>Your Coins, Levels, Booty have not been transferred to this new server.  Every one of the new 'advanced' players is starting at level 0.  It should be pretty easy to make this leaderboard even without yer old booty, so go get it! :)<br><br>Setting this system up and finding who to put in it was not fun for me.  I don't want to ban anyone or stop people from having fun in the game (especially passionate users like you guys), but keeping the game fun for the majority of players is the most important thing.<br><br>
Arrrr.....</fb:message></fb:explanation>
<?php

//}

?>

 	<div style='text-align:center; margin-bottom:0px; padding-top:10px; padding-bottom:0px'>
 	<p style='text-align:center; font-size:110%'>
 	
 	<?php if($sort_listing == 'overall') {  ?>
 		<strong>Overall</strong> 
 	<?php } else { ?>
 		<a href='leaderboard.php?sort=overall&team=<?php echo $team_listing; ?>'>Overall</a>  
	<? } ?>
 	
 	&nbsp; &nbsp; &nbsp; | &nbsp; &nbsp; &nbsp;
 	
 	
 	<?php if($sort_listing == 'coins') {  ?>
 		<strong>Most Coins</strong> 
 	<?php } else { ?>
 		<a href='leaderboard.php?sort=coins&team=<?php echo $team_listing; ?>'>Most Coins</a>  
	<? } ?>	
	
 	
 	&nbsp; &nbsp; &nbsp; | &nbsp; &nbsp; &nbsp;
 	
	<?php if($sort_listing == 'level') {  ?>
 		<strong>Highest Level</strong> 
 	<?php } else { ?>
 		<a href='leaderboard.php?sort=level&team=<?php echo $team_listing; ?>'>Highest Level</a>  
	<? } ?>
	 	
 	</p>
 </div>
 
	<?php 
	
	
	global $memcache;
	
	if($team_listing == 'overall') {
		if($sort_listing == 'overall') {
			$r = $memcache->get("leaderboard");
			if($r == FALSE) {
			$sql = "SELECT id, name, level , buried_coin_total, created_at, level * buried_coin_total b FROM users ORDER BY b DESC LIMIT 50"; 
		
			$r = $DB->GetArray($sql);
			$memcache->set("leaderboard", $r, false, 3600 * 6);
		}
	}
		else if ($sort_listing == 'coins') {
			$r = $memcache->get("leaderboard_coins");
			if($r == FALSE) {
				$sql = "SELECT id, name, level , buried_coin_total, created_at, buried_coin_total b FROM users ORDER BY b DESC LIMIT 50"; 
		
				$r = $DB->GetArray($sql);
				$memcache->set("leaderboard_coins", $r, false, 3600 * 6);
		}
	}
		else if ($sort_listing == 'level') {
			$r = $memcache->get("leaderboard_level");
			if($r == FALSE) {
				$sql = "SELECT id, name, level , buried_coin_total, created_at, level b FROM users ORDER BY b DESC LIMIT 50"; 
		
				$r = $DB->GetArray($sql);
				$memcache->set("leaderboard_level", $r, false, 3600 * 6);
		}
	}
		
	
	
	
	
	
	}
	
	
	else {
		if($sort_listing == 'overall') {
			$r = $memcache->get("leaderboard_$team_listing");
			if($r == FALSE) {
				$sql = "SELECT id, name, level , buried_coin_total, created_at, level * buried_coin_total b FROM users where team = ? ORDER BY b DESC LIMIT 50"; 
		
				$r = $DB->GetArray($sql, array($team_listing));
				$memcache->set("leaderboard_$team_listing", $r, false, 3605 * 6);
			}
		}

		else if($sort_listing == 'coins') {
			$r = $memcache->get("leaderboard_coins_$team_listing");
			if($r == FALSE) {
				$sql = "SELECT id, name, level , buried_coin_total, created_at, buried_coin_total b FROM users where team = ? ORDER BY b DESC LIMIT 50"; 
		
				$r = $DB->GetArray($sql, array($team_listing));
				$memcache->set("leaderboard_coins_$team_listing", $r, false, 3605 * 6);
			}
		}
		else if($sort_listing == 'level') {
			$r = $memcache->get("leaderboard_level_$team_listing");
			if($r == FALSE) {
				$sql = "SELECT id, name, level , buried_coin_total, created_at, level b FROM users where team = ? ORDER BY b DESC LIMIT 50"; 
		
				$r = $DB->GetArray($sql, array($team_listing));
				$memcache->set("leaderboard_level_$team_listing", $r, false, 3605 * 6);
			}
		}

		
		

		
	}
	?>
	<center>
	<table cellspacing='10px' style='margin:10px; margin-top:0px; padding-top:0px;'><tr><td valign='top'>
	<?php
	//print_r($r);
	foreach($r as $key => $value) {
		//echo "key $key";
		//echo "value $value";
		//print_r($value);
		
		$id = $value['id'];
		$score = $value['b'];
		$level = $value['level'];
		$created_at = date("F j, Y", strtotime($value['created_at']));
		$buried_coin_total = $value['buried_coin_total'];
		$rank = $key + 1;
		$buried_coin_total = number_format($buried_coin_total);
		echo "<div style='padding-top:10px'><center><table cellpadding='5px' cellspacing='5px' width='400px' border=0 style='padding:5px; padding-top:10px; border: 5px solid #3B5998; text-align:center'><tr><td width='25px' style='font-size:200%'><h1 style='font-size:200%'>$rank</h1></td><td width='75px'>";
		echo "<fb:profile-pic size='square' uid = '$id'/>";
		echo "</td><td style='font-size:150%'><fb:name uid = '$id' /><br><span style='font-size:75%'>Pirate Since $created_at</span><br>";
		
		
		if($sort_listing == 'level') {
			echo "<strong>level: $level</strong> ";
		}
		else {
			echo "level: $level ";
		}
				
		if($sort_listing == 'coins') {
			echo "<strong>gold: $buried_coin_total</strong>";
		}
		else {
			echo "gold: $buried_coin_total ";
		}
		
		echo "</td></tr></table></center></div>";
		
	}

	
?>
	
		</div>
		
		</td><td valign='top'> <br>
			
			<div>
			<?php
			print adsense_120($user);
			?>
			<div><br><br><br><div>
			<?php

				print rmx_120($user);
			?>
			
			<br>			<?php

				print adsense_200_200($user);
			?>
			
			</div>
			
		</td></tr></table></center>

		<br><br>
		<?php
		

?>
<br>

<h3 style="text-align:center; padding-top: 5px; padding-bottom: 10px"><a href="index.php">Go back to sea</a>  -adventure, danger and treasure await</h3>

<?php

//require_once "my_pirate.inc.php";

//require_once "world_stats.inc.php";
//set_profile($user);




require_once 'world_stats.inc.php';
		print adsense_468($user);

//print adsense_468($user);

?>

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


<br><br>
<fb:comments showform="true" xid="leaderboard_comments" canpost="<?php echo $canpost; ?>" candelete="<?php echo $candelete; ?>" returnurl="<?php echo $facebook_canvas_url; ?>/leaderboard.php">
   <fb:title>Pirate Leaderboard</fb:title>
 </fb:comments>
 
 



<?php
require_once 'ad_bottom.inc.php';
require_once 'footer.php'; ?>
