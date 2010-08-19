<?php

require_once 'includes.php';

global $DB;

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
<table style="background-color: #3B5998; border: 1px solid black; text-align: center; color: #FFFFFF; margin-top:20px; padding:10px" width="90%" border="0">

<tr>
<td>


<center>
	<h1 style='font-size:200%; color: #FFFFFF; padding-bottom:5px'>Throw a bomb at a Landlubber! (<?php echo $bomb_count; ?> left)</h1>
	
</center>

<?php 
	$see_all = $_REQUEST ['see'];
?>

<table><tr><td valign = 'top'>
	<div style='padding-top:70px'>
	<fb:photo pid='<?php echo $bomb_red; ?>' uid="<?php echo $image_uid; ?>" />
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

	
$app_friends = $facebook->api_client->friends_list;

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

//print_r($r[0]);
$user_1 = $r[0]['id'];
$user_1_coins = $r[0]['coin_total'];


?>


</td><td>






<h2 style="text-align:center; font-size:150%; color: #FFFFFF; padding:10px; padding-top:20px">Type a Landlubbers name to throw a bomb and steal gold from them!</h2><br>


<center>



<?php
//echo "user is $user";
$name = get_name_for_id($user);

$invfbml = <<<FBML
<a href='http://facebook.com/profile.php?id=$user'>$name</a> threw a 
bomb at you! Attack them back!
<fb:req-choice url="CHANGEME/?i=$user" label="ATTACK!" />
FBML;

?>

<fb:request-form type="Pirates" action="throw_the_bomb_action.php" content="<?=htmlentities($invfbml)?>" invite="false">

<fb:friend-selector />
<fb:request-form-submit />

</fb:request-form>


<br>

</center>


</td></tr></table>




</td>

</tr>
</table>
<br>
<?php
print adsense_468($user);
?><br>
	<h1 style='font-size:125%; color: #FFFFFF; padding-bottom:20px; padding-top:10px'><a href='throw_bomb_pick_pirate.php'>Throw a bomb at a Pirate instead</a></h1>

	<h1 style='font-size:125%; color: #FFFFFF; padding-bottom:20px; padding-top:10px'><a href='clear_action.php'>Nevermind, return to sailn'</a></h1>


</center>

<?php 


require_once 'footer.php'; ?>
