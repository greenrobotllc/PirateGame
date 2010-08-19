<?php

require_once 'includes.php';
global $enemy_city_small;

$type= get_team($user);

$in = $_REQUEST['i'];
if(isset($in)) {
	$facebook->redirect("$facebook_canvas_url/install.php?i=$in");
}
//print_r($user);
$user_in_db = is_user_in_db($user);
if($user_in_db ==0) {
	$facebook->redirect("$facebook_canvas_url/install.php");
}

print dashboard();

?>


<?php if($msg):?>
<fb:success>
     <fb:message><?php echo $msg; ?></fb:message>
</fb:success>


<?php endif; ?>	


<center>

 
 <?php
$msg2= 'Avast! You found an enemy island town.<br>Time for pillagin!'; 
 success_msg($msg2); ?>




</p></h1></div>

<table><tr><td>

<h1 style="text-align:center; padding-bottom: 0px;margin-bottom:0px"><a <?php href('pillage_town.php'); ?>>Attack the town!</a></h1>
<span style='text-align: center; padding-top:0px; margin-top:0px; padding-left:10px;padding-right:10px'>Pillage to find booty and coins</span>
<br><br>

<h1 style="text-align:center; padding-bottom: 0px;margin-bottom:0px"><a <?php href('clear_action.php'); ?>>Run Away</a></h1>
<p style='text-align: center; padding-top:0px; margin-top:0px; padding-left:10px;padding-right:10px'>Only for landlubbers...</p>
</td><td>

<?php 
image($enemy_city_small); ?>
</td></tr></table>

</center>	

<?php





print adsense_468($user);

?>

<?php require_once 'footer.php'; ?>
