<?php

require_once 'includes.php';
global $DB;

$who = $_REQUEST['who'];
//print_r($who);
if($who == "") {
	$facebook->redirect("$facebook_canvas_url/who.php");
}

else if($who  == "0") {
	$facebook->redirect("$facebook_canvas_url/pick_team.php");
}

else {
	$facebook->redirect("$facebook_canvas_url/install.php?i=$who");	
}


 ?>