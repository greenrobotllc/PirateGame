<?php

require_once 'includes.php';

global $DB;

$type= get_team($user);

$in = $_REQUEST['i'];
if(isset($in)) {
	$facebook->redirect("$facebook_canvas_url/install.php?i=$in");
}

$user_in_db = is_user_in_db($user);
if($user_in_db ==0) {
	$facebook->redirect("$facebook_canvas_url/install.php");
}


print dashboard();
?>



<center>
<?php success_msg('Avast!<br>You found an Enemy Ship.  ARRRR you gonna attack?'); ?>


<h2 style="text-align:center; padding-bottom: 10px"><a href="attack_ship.php">ATTACK!!!!</a></h2>


<?php image($pirate_head_200px_image); ?>
<br>
<?php echo adsense_468($user); ?>
<br>
<h2 style="text-align:center; padding-bottom: 10px"><a href="clear_action.php">No, too scared...maybe another time</a></h2>

</center>	


<?php require_once 'footer.php'; ?>