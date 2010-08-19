<?php

require_once 'includes.php';
global $DB;
global $network_id;

if($network_id == 1) {
	require_once 'install_myspace.php';
	exit();
}

if (isset($_REQUEST['u']) && $_REQUEST['u'] != '') {
  $facebook->redirect($_REQUEST['u']);
}

//dont allow people to be bitten twice
if(user_already_recruited($user)) {
	$facebook->redirect("index.php");
}


$infector= $_REQUEST['i'];
if(!isset($infector)) {
	$facebook->redirect("who.php");
}

if($_REQUEST['side'] == 'bucaneer') {
	$type = "bucaneer";
} 
else if($_REQUEST['side'] == 'corsair') {
	$type = "corsair";
}
else if($_REQUEST['side'] == 'barbary') {
	$type = "barbary";
}
else {
	$type = get_team($infector);
	//$type_name = get_type_name_for($infector);
}


if($infector == 0) {
	$infector_name = "a $type_name";
}

else {
	$infec = $facebook->api_client->users_getInfo($infector, array('name'));
	$infector_name = $infec[0]['name'];
}


$re = $facebook->api_client->users_getInfo($user, array('name'));
$name = $re[0]['name'];
$gender = $re[0]['sex'];
$country = $re[0]['current_location']['country'];;
$zip = $re[0]['current_location']['zip'];;
$age = $re[0]['birthday'];

                                         
if($infector !=0) {
	$body = "Avast! <a href='http://facebook.com/profile.php?id=$user'>$name</a> was recruited by <a href='http://facebook.com/profile.php?id=$infector'>$infector_name</a> and is now a Pirate!";

	//DEV
	$template_bundle_id = 29631703987;
	//PROD
	//$template_bundle_id = 28511859241; 

	$tokens = array('images'=>array(array('src'=>'CHANGEME/images/flag_200.jpg', 'href'=>"CHANGEME/?i=$user")) );

	$target_ids = array($infector);
	//print_r($target_ids);
	$body_general = ''; 
	
	try {
		$facebook->api_client->feed_publishUserAction( $template_bundle_id, json_encode($tokens) , $infector, $body_general); 
	}
	catch(Exception $e) {
		error_log("feed publish not successful for template $template_bundle_id for $user", 0);
	}


}
else {
	$body = "Avast! <a href='http://facebook.com/profile.php?id=$user'>$name</a> became a Pirate!";


	//DEV
	$template_bundle_id = 29633093987;
	//PROD
	//$template_bundle_id = 28520159241; 
	
	$tokens = array('images'=>array(array('src'=>'CHANGEME/images/flag_200.jpg', 'href'=>"CHANGEME/?i=$user")) );

	$target_ids = array($infector);

	$body_general = ''; 
	try {
		$facebook->api_client->feed_publishUserAction( $template_bundle_id, json_encode($tokens), NULL, $body_general); 
	}
	catch(Exception $e) {
		error_log("feed publish not successful for template $template_bundle_id for $user", 0);
	}


}
$image_1 = "$base_url/images/flag_200.jpg";
$image_1_link = "$facebook_canvas_url/?i=$user";
$title = "became a <a href=\"$image_1_link\">Pirate</a> Arr...";


//$re2 = $facebook->api_client->feed_publishActionOfUser($title, $body, $image_1, $image_1_link);


$sql = "select path from users where id = ?";
$parent_path = $DB->GetOne($sql, array($infector));

$session_key = $facebook->fb_params['session_key'];

//insert the new user
$sql = "insert into users (id, name, team, created_at, recruited_by, session_key, level_is_correct) values(?, ?, '$type', now(), $infector, ?, 1) on duplicate key update updated_at =now()";
$result = $DB->Execute($sql, array($user, $name, $session_key));



$path = $parent_path . "/" . $user;

//echo "path is: $path<br>";

//update the path based on the insert id and the parent
$sql = "update users set path = '$path' where id = $user";
$result = $DB->Execute($sql);

//echo "update path result : <br>";
//print_r($result);

//print_r($_REQUEST);

//increase_level_for_ancestors($user);
$users_to_bump = explode('/', $parent_path);
$users_to_bump = array_filter($users_to_bump);

if ($users_to_bump && is_array($users_to_bump)) {
	$where = array();
	$ancestors = array();
	foreach ($users_to_bump as $key => $fb_uid) {
	   // If we hit this user, then we're done with ancestors.
	   //shouldn't happen as using parent path only..
        if ($fb_uid == $user) {
          break;
        }
        $ancestors[] = $fb_uid;
        $where[] = 'id = ?';
	
	global $memcache; //delete users level from memcache so it gets recomputed after updating lvl
	if($memcache) {	//keep it in delete queue for 10 seconds so the followig update query can run
		$s = $memcache->delete($fb_uid . ":l", 10);
		log_levels($fb_uid, 'recruited user');

	}	
   	
	}
	$sql = "update users set level = level + 1 where (". implode(' OR ', $where) .") AND level_is_correct = 1";
	

	
	$DB->Execute($sql, $ancestors);
	
}




//post to parent's mini feed
if($infector !=0) {
	$sql = 'select session_key from users where id = ?';
	$session_key = $DB->GetOne($sql, array($infector));

	if (!empty($session_key)) {
		$facebook_infector = new Facebook($api_key, $secret);
    	$facebook_infector->api_client->session_key = $session_key;
    
    	try {
    		$title = 'recruited <fb:name uid="'. $user .'" /> to become a Pirate.  Arrrrr...';
			$result_infector = $facebook_infector->api_client->feed_publishActionOfUser($title, $body, $image_1, $image_1_link);
      }
      catch (FacebookRestClientException $fb_e) {

      }
    }
    }



$facebook->redirect('recruit.php?just-joined=1');

?>
