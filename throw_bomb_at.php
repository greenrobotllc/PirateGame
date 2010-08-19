

<?php

require_once 'includes.php';
global $DB;
redirect_to_index_if_not($user, "bomb");

$type= get_team($user);

$app_friends = $facebook->api_client->friends_list;

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

$throw_at = $_REQUEST['id'];
if(empty($throw_at)) {
	$facebook->redirect("$facebook_canvas_url/throw_bomb_pick.php");
}

$is_friend = false;
foreach ($app_friends as $value)
{
	if($value == $throw_at) {
		$is_friend = true;
	}
}


?>
<style>

	.throw_at a {
		color:#FFFFFF;
	}
	
</style>
<?php

print dashboard();


?>



<?php 

$msg = "You see the pirate <fb:name uid='$throw_at'> in the distance...  Wanna throw a bomb?";

$bomb_count = get_bomb_count($user);
if($bomb_count == "") {
	$facebook->redirect("index.php");
}

?>
<center>

<table style="background-color: #3B5998; border: 1px solid black; text-align: center; color: #FFFFFF; margin-top:20px; padding:10px" width="90%" border="0">
<tr><td colspan=3>


<center>
	<h1 style='font-size:200%; color: #FFFFFF; padding-bottom:10px; text-align:center'>Throw a bomb!</h1>
</center>

</td></tr>

<tr>

<td>






<tr>
<td><center>
<a style="color:#FFFFFF" href="throw_bomb_pick.php">
<span style="font-size:125%">Pick someone else!<br>(Arrr....)</span>
</a>

</center>
</td>

<td style="text-align:center">
	<fb:photo pid='<?php echo $ship_image_blue; ?>' uid="<?php echo $image_uid; ?>" />
  
  
<center>
<form action="throw_the_bomb_action.php">
<br>
<input type='hidden' id="throw_at" name='throw_at' value='<?php echo $throw_at; ?>' />
<input style="background-color: #FFFFFF; width:80px; height:60px; font-size: 140%; margin-top:20px"  type="submit" value="Throw!" name="submit"/>
</form>
</center>

</td>
<td>
<center>

<fb:profile-pic size='normal' uid="<?php echo $throw_at; ?>" /><br>

<div id='throw_at' style='background-color:#FFFFFF; color: black; margin:10px; padding:10px'>
<?php

$coin_total = get_coin_total($throw_at);
$team = get_team($throw_at);
if(empty($coin_total)) {
$coin_total = "??";
}
if(empty($team)) {
	$team_text = 'A Landlubber';
}
else {
	$team_text = "A $team Pirate";
}
?>

<fb:name uid="<?php echo $throw_at; ?>" /><br>
<?php echo $team_text; ?><br>
<?php echo $coin_total; ?> coins<br>

</div>

</center>
</td>
</tr>




</td>

</tr>
</table>

</center>
<br>

<?php

//require_once "my_pirate.inc.php";

//require_once "world_stats.inc.php";
//set_profile($user);





?>


<?php require_once 'footer.php'; ?>