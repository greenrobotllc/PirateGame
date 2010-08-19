<?php

require_once 'includes.php';


redirect_to_index_if_not($user, "island");

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

//redirect_to_index_if_not($user, "bomb");

print dashboard();


?>



<?php 

//$msg = "You see an enemy in the distance...  Wanna throw a bomb?";

$dynamite_count = get_dynamite_count($user);
$monkey_count = get_monkey_count($user);

if($dynamite_count == "" || $monkey_count == "") {
	$facebook->redirect("index.php");
}

?>
<center>
<table style="background-color: #3B5998; border: 1px solid black; text-align: center; color: #FFFFFF; margin-top:20px; padding:10px" width="90%" border="0">

<tr>
<td>


<center>
	<h1 style='font-size:200%; color: #FFFFFF; padding-bottom:10px'>Leave a monkey - dynamite trap!</h1>
	
</center>



<table><tr><td style='color: #FFFFFF;'>
	
<?php image($item_dynamite_small); ?><br>(<?php echo $dynamite_count; ?> dynamite left)


</td><td>
<h2 style="text-align:center; font-size:150%; color: #FFFFFF; padding:10px; padding-top:20px">Type a friends name to leave a dynamite booby trap for them.
</h2>
<h2 style="text-align:center; font-size:150%; color: #FFFFFF; padding:10px; padding-top:20px">
When they set it off, they'll explode and lose some of their booty.</h2>
<h2 style="text-align:center; font-size:150%; color: #FFFFFF; padding:10px; padding-top:20px"> Your monkey will wait here and carry some of the pillaged booty back to you!</h2><br>

<center>
<form action="leave_dynamite_action.php">
<fb:friend-selector/>
<br>
<input style="background-color: #FFFFFF; width:150px; height:50px; font-size: 140%; margin-top:20px"  type="submit" value="Leave Dynamite" name="submit"/>
</form>
</center>

</td>



<td>
<?php if($monkey_count == 1) {
	$monkey_text = 'monkey';
}
else {
	$monkey_text = 'monkeys';
}

 image($item_monkey_small); ?>
<br>(<?php echo $monkey_count; ?> <?php echo $monkey_text; ?> left)
</td>

</tr></table>




</td>

</tr>
</table>

</center>
<br>



<?php 

print adsense_468($user);
require_once 'footer.php'; ?>