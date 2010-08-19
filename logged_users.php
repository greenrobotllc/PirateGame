<?php

include_once '../client/facebook.php';
// some basic library functions
include_once 'lib.php';
// this defines some of your basic setup
include_once 'config.php';
global $DB;
require_once 'header.php';

$type= get_team($user);

//print_r(get_audited_users());



//print_r(get_audited_users());

$user_in_db = is_user_in_db($user);
if($user_in_db ==0) {
	$facebook->redirect("$facebook_canvas_url/install.php");
}



print dashboard();
?> <center><h1>Pirates being Logged</h1><?php

$a = get_audited_users();

//print_r($a);
echo "<table cellpadding=3 border=1>";
foreach($a as $key => $value) {
	//print_r($value);
	$id = $value['id'];
	$level = $value['level'];
	echo "<tr><td>";
	echo "<a href='activity.php?id=$id'><fb:name ifcantsee='anonymous' uid='$id' linked='false' /></a></td><td>level: $level</td>";
	echo "<td>id: $id</td>";
	if(in_array($user, get_moderators())) {
		echo "<td><a href='remove_from_log.php?id=$id'>remove</a></td>";
	}
	echo "</tr>";
}
echo "</table>";

?>

</center>