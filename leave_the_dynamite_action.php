<?php

require_once 'includes.php';
global $DB;

redirect_to_index_if_not($user, "island");

update_action($user, "left_dynamite");

$to_id = $_REQUEST['throw_at'];
if(!isset($to_id)) {
	$facebook->redirect("index.php");
	//print_r($_REQUEST);
}


//print_r($_REQUEST);

	$body = "<fb:name uid=$user' capitalize = 'true' /> left a dynamite trap for <fb:name uid='$to_id' />.   <a href='$facebook_canvas_url/?i=$user'>Arrrrr...</a>.";

//$image_1 = "$base_url/images/flag_200.jpg";
//$image_1_link = "$facebook_canvas_url/?i=$user";
$title = "<a href='$facebook_canvas_url/?i=$user'>played with dynamite</a>.";


//for the user
try {

$re2 = $facebook->api_client->feed_publishActionOfUser($title, $body);
}
   catch (FacebookRestClientException $fb_e) {

      }
      
//for the user being dynamited

$sql = 'select session_key from users where id = ?';
$session_key = $DB->GetOne($sql, array($to_id));
  

$query = "INSERT INTO booby_traps (from_id, to_id, created_at) VALUES ('". $user . "', '" . $to_id . "', now())";
		$DB->Execute($query);
	
	

$facebook->redirect("$facebook_canvas_url/left_dynamite.php?to=$to_id&sent=1");

//}

	
	 

 ?>