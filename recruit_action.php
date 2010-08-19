<?php

include_once '../client/facebook.php';
// some basic library functions
include_once 'lib.php';

// this defines some of your basic setup
include_once 'config.php';
global $DB;

$inviteList = array();
$max_who = $_REQUEST['max_who'];


	for ($i = 0, $x = 0; $i <= $max_who && $x < 10; $i++)
	{
		//print 'who-' . $i . ": ";
		if ($_REQUEST['who-' . $i] != null)
		{
			$inviteList[$x++] = $_REQUEST['who-' . $i];
			//print $_REQUEST['who-' . $i];
		}
		//print '<br />';
	
	}
	foreach ($inviteList as $value)
	{
		$query = "INSERT INTO invite_requests (from_id, to_id, created_at) VALUES ('". $user . "', '" . $value . "', now())";
		$DB->Execute($query);
	}
	
	//print_r($REQUEST);
	//echo "inviteLLIST; " . sizeof($inviteList);
	if(sizeof($inviteList) == 0) {
		//$facebook->redirect("$facebook_canvas_url/recruit.php");
		die("OK");
	}
	//print_r($inviteList);
	
	$to = $inviteList;
	//$vorw = get_type_for($user);
	$title = "Pirate"; //get_type_name_for($user);
	$content = "<fb:req-choice url=\"$facebook_canvas_url/install.php?i=" . $user . "\" label=\"Arrrrr!\" /> Ahoy!...wanna be a pirate?  Fight other pirates on the high seas of Facebook in search of booty and adventure!";
	


	$request = "request";
	$image = "$base_url/images/flag_200.jpg";
	$confirmUrl = $facebook->api_client->notifications_sendRequest ($to, $title, $content, $image, $request);
	if($confirmUrl != "") {
		$facebook->redirect($confirmUrl);
	}
	else {
		$facebook->redirect("$facebook_canvas_url/index.php?msg=send-limit");
	}
	
	
 

 ?>