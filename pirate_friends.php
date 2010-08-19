<?php

include_once '../client/facebook.php';
// some basic library functions
include_once 'lib.php';
// this defines some of your basic setup
include_once 'config.php';
global $DB;
require_once 'header.php';

$type= get_team($user);
$app_friends = $facebook->api_client->friends_list;

if($_REQUEST['sent'] == "1") {
	$msg = "Yer Pirate invitations have been sent!";
}


if($_REQUEST['msg'] == "send-limit") {
	$msg = "Your requests were not sent.  You're over the limit for today.  Try again later. :(";
}

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

if($msg):?>
<fb:success>
     <fb:message><?php echo $msg; ?></fb:message>
</fb:success>
<?php endif; ?>	


<fb:tabs>
	<fb:tab_item href="my_mates.php" title="My Recruits"/>
	<fb:tab_item href="pirate_friends.php" title="Pirate Friends" selected="true"/>
</fb:tabs><br>

		<div style="position:relative; text-align:center">
		
			<h1 style="padding-bottom: 0px; margin-bottom:5px; font-size:250%">Pirate Friends</h1>
			
			<p style='margin-top: 0px; text-align:center; padding-top:0px; padding-bottom:10px; font-size:150%'>See all of your friends who are pirates.</p>
			
	<?php 

	if(count($app_friends) == 0) {
		$facebook->redirect("recruit.php");
	}
	
	$friend_string = implode(",", $app_friends);
	
	//print adsense_468($user);
	//print "<br>";

?>
			<table width='100%' cellpadding='5px'><tr><td>
<?php

	global $memcache;
	$r = $memcache->get("$user:p");
	if($r == false) {
		$sql = "select id, level, coin_total, buried_coin_total from users where id in ($friend_string);";
		$r = $DB->GetArray($sql);
		
		$memcache->set("$user:p", $r, false, 3600 * 24);
	}
		
	foreach($r as $key => $value) {
		$id = $value['id'];
		$level = $value['level'];
		$buried_coin_total = $value['buried_coin_total'];
		$rank = $key + 1;
		echo "<div><center><table style='border: 5px solid #3B5998; text-align:center; padding:5px; padding-top:10px' cellpadding='5px' cellspacing='5px' width='400px' border=0><tr><td width='25px' style='font-size:200%'><h1 style='font-size:200%'>$rank</h1></td><td width='75px'>";
		echo "<fb:profile-pic size='square' uid = '$id'/>";
		echo "</td><td style='font-size:150%'><fb:name uid = '$id' /><br>level: $level gold: $buried_coin_total";
		echo "</td></tr></table></center></div>";
	}
	
?>
	
	
</td><td valign='top'>
<?php print rmx_120($user); ?>
</td></tr></table>

	
	
		</div>
<br>
<center>
<div style='text-align:center; font-size:150%; padding:5px'>Give your mates this link to recruit em<br>
<?php echo $facebook_canvas_url; ?>/?i=<?php echo $user; ?></div>
</center>


<h3 style="text-align:center; padding-top: 5px; padding-bottom: 10px"><a href="index.php">Go back to sea</a>  -adventure, danger and treasure await</h3>



<?php 
require_once 'ad_bottom.inc.php';

require_once 'footer.php'; ?>