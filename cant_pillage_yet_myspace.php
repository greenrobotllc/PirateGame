<?php

require_once 'includes.php';

$type= get_team($user);

$crew = $DB->GetOne('select level from upgrades where user_id = ? and upgrade_name = ?', array($user, 'crew'));

if($crew > 5) {
//	$facebook->redirect('index.php');
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

update_action($user, "NULL");

print dashboard();

?>

<center>
<?php


?><br>
<h1>Ye can't be pillaging without a crew!</h1><br>
<h1>Crew members cost 400 coins at the  <a href='shipyard.php'>shipyard</a></h1>
<br>
<h1>You have <?php echo get_coin_total($user); ?> coins.  Find more gold on deserted islands.</h1>

<br>
<br>
<!-- input type="text" onfocus="this.select()" onclick="this.select()" value="CHANGEME/?i=<?php echo $user; ?>" style="width: 700px;"/ -->

<br>

<h2 style="text-align:center; padding-bottom: 10px"><a <?php href('shipyard.php'); ?> ">Go to Shipyard to buy a crew member</a></h2><br>

<h2 style="text-align:center; padding-bottom: 10px"><a <?php href('index.php'); ?> ">Back to Sailin</a></h2>


</center>	


<?php require_once 'footer.php'; ?>
