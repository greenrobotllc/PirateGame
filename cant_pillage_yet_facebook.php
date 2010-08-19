<?php

require_once 'includes.php';

$type= get_team($user);

$crew = $DB->GetOne('select level from upgrades where user_id = ? and upgrade_name = ?', array($user, 'crew'));

if($crew > 5) {
	$facebook->redirect('index.php');
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

$pillage_invites = $DB->GetOne('select pillage_invites from users where id = ?', array($user));

 //echo "pillage invites $pillage_invites"; 
if($pillage_invites > 2) {
	$DB->Execute('update users set pillage_invites = 0 where id = ?', array($user));
	buy_upgrade($user, 'crew');
	$facebook->redirect('index.php?msg=pillage-invite-crew');
}

?>
<h1>Ye can't be pillaging without a crew!</h1><br>
<?php $friends_left = 3 - $pillage_invites; ?>
<h1>Invite 3 friends to be Pirates to gain yer first crew member!</h1><br>
<h1>You have <?php echo $friends_left; ?> friends left to invite before getting a crew member</h1>

<?php

//  Get list of friends who have this app installed...
$rs = $facebook->api_client->fql_query("SELECT uid FROM user WHERE has_added_app=1 and uid IN (SELECT uid2 FROM friend WHERE uid1 = $user)");
$arFriends = "";

//  Build an delimited list of users...
if ($rs)
{
	for ( $i = 0; $i < count($rs); $i++ )
	{
		if ( $arFriends != "" )
			$arFriends .= ",";
	
		$arFriends .= $rs[$i]["uid"];
	}
}



$sNextUrl = urlencode("&i=".$user);

//  Build your invite text
$invfbml = <<<FBML
You have been invited to join Pirates!<br>
<fb:name uid="$user" firstnameonly="false" shownetwork="false"/> wants you to become a Pirate. Pillage, plunder, and sail on the high seas!
<fb:req-choice url="http://www.facebook.com/add.php?api_key=$api_key&next=$sNextUrl" label="Become a Pirate!" />
FBML;

?>


<div style="padding: 10px;">  <fb:request-form method="post" action="pillage_invite.php" content="<?=htmlentities($invfbml)?>" type="Pirate" invite="true">  <div class="clearfix" style="padding-bottom: 10px;">  <fb:multi-friend-selector condensed="true" exclude_ids="<?=$arFriends?>" style="width: 200px;" />  </div>  <fb:request-form-submit />  </fb:request-form> </div>

<br>
<?php
//<!-- h2>...Or Sail into the <a href="harbor.php">Harbor</a> and hire some crew members<br>
//If you need coins, sail and look on deserted islands!</h2><br -->
?>
<br>
<table border="0">
<tr><td>
<h4>Pillaging Tips:</h4><br>
The more crew you have the more money you'll make.<br>
The less houses you attack the higher chance you'll win.<br>
Crew can be killed if your attack fails!  It's rare but it happens!<br>
</td></tr>
</table>

<br>
<h2 style="text-align:center; padding-bottom: 10px"><a href="index.php">Back to Sailin</a></h2>


</center>	


<?php require_once 'footer.php'; ?>
