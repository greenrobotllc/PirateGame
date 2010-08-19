<?php
require_once 'includes.php';
global $network_id;

if($network_id == 0) {
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

$sNextUrl = urlencode("?i=".$user);

//  Build your invite text
$invfbml = <<<FBML
You have been invited to join Pirates!<br>
<fb:name uid="$user" firstnameonly="false" shownetwork="false"/> wants you to become a Pirate. Pillage, plunder, and sail on the high seas!
<fb:req-choice url="http://www.facebook.com/tos.php?api_key=$api_key&next=$sNextUrl" label="Become a Pirate!" />
FBML;
?>

<fb:request-form type="Pirates" action="index.php?c=skipped" content="<?=htmlentities($invfbml)?>" invite="true">
	<fb:multi-friend-selector max="20" actiontext="Invite your friends to increase your Pirate level!" showborder="true" rows="5" exclude_ids="<?=$arFriends?>">
</fb:request-form>

<?php

}
else {

print dashboard();
?>
<center>

<h1>Give yer mates this link to recruit em!</h1>
<br>
<input type="text" onfocus="this.select()" onclick="this.select()" value="http://dev.greenrobot.com/pirates/myspace/?i=<?php echo $user; ?>" style="width: 700px;"/>
<br>

<table><tr><td>
<h2>For every friend that joins, you'll gain one level!<br><br>For every friend your friend recruits you'll also get a level.</h2>
</td><td>
<?php
echo image($pirate_head_200px_image);

}

?>
</td></tr></table>

</center>
