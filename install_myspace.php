<?php
//print_r($_REQUEST);

require_once 'includes.php';

global $DB, $user;
//echo "user; " ;
//print_r($user);
//print_r($_REQUEST);
//exit();
if(user_already_recruited($user)) {
	$facebook->redirect("index.php");
}

	require_once('Space.php');
  	$key = 'CHANGEME';
  	$secret = 'CHANGEME';
  	//$print_r($user);
  	$s = new Space($key,$secret);
  	//print_r($s);
	

	

 

try {
	$re = $s->profile($user);
}
catch(Exception $e) {
			//print_r($_REQUEST);
			echo "<p style='font-size:150%; text-align:center'><a href='http://profile.myspace.com/index.cfm?fuseaction=user.viewprofile&friendid=329304714' target='_top' >Install Pirates to Play!</a></p>";
			exit();
}

$infector= $_REQUEST['i'];
if(!isset($infector)) {
	//check for cookie
	$pirates_referer = $_COOKIE['pirates_ref'];
	if(isset($pirates_referer)) {
		//check if this is a valid user
		if(is_user_in_db($pirates_referer)) {
			$infector = $pirates_referer;
		}
		else {
			$facebook->redirect("pick_team.php");		
		}
	}
	else {
		$facebook->redirect("pick_team.php");
	}
}


//print_r($_REQUEST);

//get path for the infector

if($_REQUEST['side'] == 'bucaneer') {
	$type = "bucaneer";
} 
else if($_REQUEST['side'] == 'corsair') {
	$type = "corsair";
}
else if($_REQUEST['side'] == 'barbary') {
	$type = "barbary";
}
else if($infector != ''  && $infector != 0) {
	$type = get_team($infector);
	//$type_name = get_type_name_for($infector);
}



if($infector == 0) {
	$infector_name = "a $type_name";
}

else {
  	//print_r($user);
  	//$hProfile = $s->profile($infector);
	$infector_name = 'unknown';
	//$hProfile['displayname'];
	
	//$infec = $facebook->api_client->users_getInfo($infector, array('name'));
	//$infector_name = $infec[0]['name'];
}


//$re = $facebook->api_client->users_getInfo($user, array('name'));
//$name = $re[0]['name'];
//$gender = $re[0]['sex'];
//$country = $re[0]['current_location']['country'];;
//$zip = $re[0]['current_location']['zip'];;
//$age = $re[0]['birthday'];


$name = $re['displayname'];
$gender = $re['gender'];$country = $re['US'];
$zip = $re['zip'];
$age = $re['age'];
	
                                         


$sql = "select path from users where id = ?";
$parent_path = $DB->GetOne($sql, array($infector));

$session_key = 1; //$facebook->fb_params['session_key'];

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





header('Location: index.php?just-joined=1');

?>
