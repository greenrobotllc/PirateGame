<?php

require_once 'includes.php';
global $network_id;
if($network_id != 1) {
	//not allowed here
	exit();
}
$type= get_team($user);

if($type == "corsair") {
  $ship_image_blue = $ship_corsair_blue_image;
}
else if($type == 'buccaneer') {
  $ship_image_blue = $ship_bucaneer_blue_image;
}
else if ($type == "barbary") {
  $ship_image_blue = $ship_barbary_blue_image;
}

//$r = explore($user);
//echo "r; $r";

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

redirect_to_index_if_not($user, "bomb");

print dashboard();


?>



<?php 

$msg = "You see an enemy in the distance...  Wanna throw a bomb?";

$bomb_count = get_bomb_count($user);
if($bomb_count == "") {
	$facebook->redirect("index.php");
}

?>
<center>

<table style="background-color: #3B5998; border: 1px solid black; text-align: center; color: white; margin-top:20px; padding:10px" width="90%" border="0">

<tr>
<td>


	<h1 style='font-size:200%; color: white; padding-bottom:5px'>Throw a bomb at a Pirate! (<?php echo $bomb_count; ?> left)</h1>
	

<?php 
	$see_all = $_REQUEST ['see'];
?>

<center>
<table style='text-align: center;'>
<tr><td valign = 'top'>
	<div style='padding-top:40px'>
	




<?php
//echo "user is $user";
$name = get_name_for_id($user);

$invfbml = <<<FBML
<a href='http://facebook.com/profile.php?id=$user'>$name</a> threw a bomb at you! Attack him back!
<fb:req-choice url="CHANGEME/?i=$user" label="ATTACK!" />
FBML;

?>



<br>



	<?php image($bomb_red); ?>
	</div>
<?php
if($see_all == 'more') 
{
?>

	<br><br>
	
<?php
	print adsense_120($user);
}
else {
	//print adsense_300_250($user);


}

	
$app_friends = get_friends($user);
//print_r($app_friends);
$r = $app_friends;
if($see_all == 'more') {
	$limit = 100;
}
else {
	$limit = 5;
}

shuffle($r);
$r = array_slice($r, 0, $limit);	
	
/*
	$friend_string = implode(",", $app_friends);
	if($see_all == 'more') {
		$limit = 100;
	}
	else {
		$limit = 5;
	}
	
	
	global $memcache;
	$r = $memcache->get("$user:bomb_top_$limit");
	if($r == false) {
		$sql = "select id, coin_total from users where id in ($friend_string) and coin_total > 0 order by coin_total desc limit $limit";
		$r = $DB->GetArray($sql);
		
		$memcache->set("$user:bomb_top_$limit", $r, false, 1);
	}
*/
//print_r($r[0]);
$user_1 = $r[0]['userid'];

$user_1_coins = $r[0]['coin_total'];
//echo "ok:";
//print_r($r);
?>


</td><td valign='top'>
<div style='padding-top:50px'
<h2 style="text-align:center; font-size:150%; color: white; padding:5px;">Some friends who have gold:</h2></div><br>






<?php if($user_1 == FALSE) { 

echo '<div style="padding:20px;text-align:center"><h2 style="color:white">no friends have any gold, pick a landlubber to continue</h2></div>'; 

}

else {
?>


<?php


foreach ($r as $users) {
	$user_id = $users['userid'];
	$name = $users['displayname'];
	$user_coins = $users['coin_total'];
	?>
	
<h2 style="text-align:center; font-size:120%; background-color: white; padding:5px; margin:5px">
<?php echo $name; ?><a href='throw_the_bomb_action.php?friend_selector_id=<?php echo $user_id; ?>&enemy_name=<?php echo $name; ?>'><br>THROW BOMB!</a></h2><br>
<?
}


}

?>



<h3 style="text-align:center;  color: white; padding:0px;"><a style='color:white' href='?see=more'>See more friends with gold!</a></h3><br>




</td></tr></table>
</center>



</td>

</tr>
</table>

<br>
<?php 
print adsense_468($user);
?>
<br>
	<h1 style='font-size:125%; color: white; padding-bottom:20px; padding-top:10px'><a href='throw_bomb_pick_landlubber.php'>Throw a bomb at a Landlubber instead</a></h1>

	<h1 style='font-size:125%; color: white; padding-bottom:20px; padding-top:10px'><a href='clear_action.php'>Nevermind, return to sailn'</a></h1>

</center>


<?php 


require_once 'footer.php'; ?>