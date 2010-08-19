<?php

require_once 'includes.php';
global $DB;

?>


<style>
#friends {
background:#FFFFFF none repeat scroll 0%;
border:1px solid #CCCCCC;
height:250px;
overflow:auto;
padding:10px;
}
.friend_box {
display:inline;
float:left;
height:50px;
margin-bottom:10px;
margin-right:20px;
overflow:hidden;
width:120px;
}
.friend_box input {
margin-left:5px;
}
.friend_image {
display:inline;
float:left;
height:50px;
overflow:hidden;
width:50px;
}
.friend_image img {
cursor:pointer;
width:50px;
}
.friend_name {
color:#555555;
display:inline;
float:left;
font-weight:bold;
height:30px;
margin-left:5px;
margin-top:3px;
overflow:hidden;
width:65px;
}
.img_fill_width {
width:50px;
}

</style>
<center>
<h1 style="font-size:150%; padding-top: 20px">Did anyone recruit you?</h1>

<h2>Yo-Ho-Ho! If anyone told you about Pirates, pick them here!</h2>


</center>
<div id="whorecruited" style="margin-left:50px; padding-top: 20px">


<form action="who_action.php" name="who" id="who">

<div class="friend_box">
            <div class="friend_name"><b>Nobody</b></div>
            <input type="radio" value="0" name="who"/>
        </div>
        
<?php
$fql ="SELECT name, pic_square, uid FROM user
WHERE uid IN (SELECT uid2 FROM friend WHERE uid1=$user)";
//print $fql;
try {
	$re = $facebook->api_client->fql_query($fql);
}
catch (exception $e) {
	//do nothing
}

//if(!$re) {
//	$facebook->redirect("https://register.facebook.com/findfriends.php?tabs&ref=friends");
//}

foreach($re as $key => $value) {
	//echo $key;
	$name = $value['name'];
	$pic = $value['pic_square'];
	$uid = $value['uid'];
	$max_who = $key;
	//$pic = $re[0]['pic_square'];
	if($pic != "") {
	?>


	<div style="display: block;" class="friend_box" id="friend-<?php echo $uid; ?>"><div class="friend_image"><fb:profile-pic uid='<?php echo $uid; ?>' /></div><div class="friend_name"><span><?php echo $name; ?></span></div><input name="who" type="radio" value="<?php echo $uid; ?>" id="<?php echo $uid; ?>"/></div>
	
<?
}
}


?>



<input type="hidden" value="<?php echo $max_who; ?>" name="max_who"/>

<br><div style="clear:both">&nbsp;</div>
<br>

<center>
<input style="text-align: center; width:100px; height:75px; font-size:150%; margin-bottom: 20px" type="submit" class="inputsubmit" value="Update" name="submit"/>

<table>

<tr>
<td>
<fb:photo pid='<?php echo $piratey_medium; ?>' uid="<?php echo $image_uid; ?>" />

</td>

<td>
<h3>Why choose? You both benefit: they'll get credit for the recruitment and you'll get an ally in battle, making you stronger!</h3>

</td>

</tr>

</table>

</center>
</form>
</div>

<?php require_once 'footer_nolinks.php'; ?>