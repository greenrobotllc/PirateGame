<?php
ob_start(); 
?>

<style>

	.throw_at a {
		color:#FFFFFF;
	}
	.text_desc a {
		color: #FFFFFF;
	}
	
</style>

<?php

require_once 'includes.php';
global $DB;

redirect_to_index_if_not($user, "island");

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
	$facebook->redirect("$facebook_canvas_url/leave_dynamite_pick.php");
}

$is_friend = false;
foreach ($app_friends as $value)
{
	if($value == $throw_at) {
		$is_friend = true;
	}
}
if($is_friend == false) {
	update_action($user, "NULL");
	$facebook->redirect("$facebook_canvas_url/index.php?msg=hack");
}

//print_r($user);
$user_in_db = is_user_in_db($user);
if($user_in_db ==0) {
	$facebook->redirect("$facebook_canvas_url/install.php");
}

//redirect_to_index_if_not($user, "dynamite");

print dashboard();


?>



<?php 


$dynamite_count = get_dynamite_count($user);
if($dynamite_count == "") {
	$facebook->redirect("index.php");
}

?>
<center>

<table style="background-color: #3B5998; border: 1px solid black; text-align: center; color: #FFFFFF; margin-top:20px; padding:10px" width="90%" border="0">
<tr><td colspan=3>


<center>
<?php if(empty($team)) { 
?>

	<h1 style='font-size:200%; color: #FFFFFF; padding-bottom:10px; text-align:center'>Launch a monkey - dynamite attack!</h1>
</center>

<?php }

else {
?>
	<h1 style='font-size:200%; color: #FFFFFF; padding-bottom:10px; text-align:center'>Leave a monkey - dynamite trap!</h1>
</center>

<?php
}
?>
</td></tr>

<tr>

<td>






<tr>
<td><center>
<a style="color:#FFFFFF" href="leave_dynamite_pick.php">
<span style="font-size:125%">Pick someone else!<br>(Arrr....)</span>
</a>

</center>
</td>

<td style="text-align:center; color:#FFFFFF">

<?php
$team = get_team($throw_at);

 if(empty($team)) { 
?>


<p style = 'font-size:130%; padding:10px' class = 'text_desc'>Send yer monkey to attack  <fb:name firstnameonly='true' uid="<?php echo $throw_at; ?>" />.<br><br>Your monkey will go after <fb:name firstnameonly='true' uid="<?php echo $throw_at; ?>" /> looting you some coins.
</p>


<?php

}

else {
?>

<p style = 'font-size:130%; padding:10px' class = 'text_desc'>When <fb:name firstnameonly='true' uid="<?php echo $throw_at; ?>" /> visits this island there's a chance <fb:pronoun uid="<?php echo $throw_at; ?>" />'ll set off the trap!<br><br>Their ship will explode, and your monkey will collect some BOOTY!
</p>


<?php
}
?>



<center>
<form action="leave_the_dynamite_action.php">
<br>
<input type='hidden' id="throw_at" name='throw_at' value='<?php echo $throw_at; ?>' />
<input style="background-color: #FFFFFF; width:160px; height:60px; font-size: 140%; margin-top:20px"  type="submit" value="Leave Dynamite!" name="submit"/>
</form>
</center>

</td>
<td>
<center>

<fb:profile-pic size='normal' uid="<?php echo $throw_at; ?>" /><br>

<div id='throw_at' style='background-color:#FFFFFF; color: black; margin:10px; padding:10px'>
<?php

$coin_total = get_coin_total($throw_at);
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
?>


<?php 
print adsense_468($user);
require_once 'footer.php'; ?>