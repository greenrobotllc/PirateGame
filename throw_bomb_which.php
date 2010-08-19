<?php

require_once 'includes.php';

global $network_id;
if($network_id == 1) {
	require_once 'throw_bomb_myspace.php';
	exit;
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



$bomb_count = get_bomb_count($user);
if($bomb_count == "") {
	$facebook->redirect("index.php");
}

?>
<center>
<?php

$msg = $_REQUEST['msg'];

if($msg == 'this-user-cant-be-bombed') {
	$msgtext = "This user can't be bombed! Pick someone else to bomb.";
}

if($msg) { ?>

<fb:error message="<?php echo $msgtext; ?>" />

<?php } ?>
<table style="background-color: #3B5998; border: 1px solid black; text-align: center; color: #FFFFFF; margin-top:20px; padding:10px" width="90%" border="0">

<tr>
<td>


<center>
	<h1 style='font-size:200%; color: #FFFFFF; padding-bottom:20px'>Throw some bombs! (<?php echo $bomb_count; ?> left)</h1>


	<h1 style='font-size:150%; color: #FFFFFF; padding-bottom:20px'><a <?php href('throw_bomb_pick_pirate.php'); ?> style='color:#FFFFFF'>Throw a bomb at a Pirate</a></h1>

	<h1 style='font-size:150%; color: #FFFFFF; padding-bottom:20px'><a <?php href('throw_bomb_pick_landlubber.php'); ?> style='color:#FFFFFF'>Throw a bomb at a Landlubber</a></h1>

	<?php image($bomb_red); ?>
	<h1 style='font-size:125%; color: #FFFFFF; padding-bottom:20px; padding-top:10px'><a <?php href('clear_action.php'); ?> style='color:#FFFFFF'>Nevermind, return to sailn'</a></h1>
	
	
</center>

</td>

</tr>

</table>
<br>
<?php 

print adsense_468($user);

require_once 'footer.php'; ?>