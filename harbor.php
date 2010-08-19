<?php

require_once 'includes.php';

$type= get_team($user);
$level = get_level($user);

if($level > 5000) {
	$active_users = $memcache->get('active_hi_3');
}
else if($level > 1000) {
	$active_users = $memcache->get('active_hi_2');
}
else if($level > 100) {
	$active_users = $memcache->get('active_hi');
}
else {
	$active_users = $memcache->get('active_lo');
}

if(!is_array($active_users)) {
	$active_users = array();
}

foreach($active_users as $u => $value) {
	if ($user == $value) {
		//print $u;
		  unset($active_users[$u]);
		  $active_users = array_values($active_users);
		  if($level > 5000) {
  			$active_users = $memcache->set('active_hi_3', $active_users);
  		  }
 		  else if($level > 1000) {
  			$active_users = $memcache->set('active_hi_2', $active_users);
  		  } 	
		  else if($level > 100) {
  			$active_users = $memcache->set('active_hi', $active_users);
  		  }  		  	  
  		  else {
			$active_users = $memcache->set('active_lo', $active_users);
  		  }
	}
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

print dashboard();
?>


<?php if($msg):?>
<fb:success>
     <fb:message><?php echo $msg; ?></fb:message>
</fb:success>
<?php endif; ?>	

<?php 

?>
<center>
<h1>Avast!</h1>
</center>


<h2 style="text-align:center;">Now entering a <?php echo ucwords($type); ?> friendly harbor ...Arrrrr...</h2>

<h3 style="text-align:center; padding-top: 10px; padding-bottom: 10px;"><a <?php href('shipyard.php'); ?>">Shipyard</a> -purchase upgrades for yer ship<br>
<a <?php href('tavern.php'); ?>">Pirate Tavern</a> - gambling, boozing, relaxing<br>


<a  <?php href('pirate_market.php'); ?>>Pirate Market</a> - trade booty for level increases<br>

<a  <?php href('leaderboard.php'); ?>>Pirate Roster</a> - check out the top pirates on facebook<br>

<?php if($network_id == 0) {
?>

<a <?php href('item_exchange.php'); ?>>The Trading Post</a> - buy and sell your items! (beta)<br>

<?php } ?>
</h3>
<br>
<center>

  <?php image($harbor_image); ?>
	<br><br>


<h3 style="text-align:center; padding-top: 10px; padding-bottom: 10px;"><a <?php href('index.php'); ?>">Leave this harbor</a> -the open sea awaits....</h3>

<?php

?>

<?php

?>

<?php
require_once 'ad_bottom.inc.php';
require_once 'footer.php'; ?>
