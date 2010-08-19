<?php

//print_r($_REQUEST);

global $random_string, $network_id;
//print_r("networkid: $network_id ");

if($network_id == 1) {
global $user;
$pirates_key = $_COOKIE['pirates_key'];
$pirates_user_id = $_COOKIE['pirates_user_id'];

if(isset($_REQUEST['i'])) {
	//echo "ok";
	$user = $pirates_user_id;
	return;

}
	$random_string = 'arrrrr!'; //this should be random based on user and stored
//print_r($_REQUEST);


if(isset($_REQUEST['opensocial_viewer_id']) && $pirates_user_id !=	$_REQUEST['opensocial_viewer_id'] ) {
	//echo '34444';
	//if( $pirates_user_id !=	$_REQUEST['opensocial_viewer_id'] ) {
		//echo 'delete';
		header("p3p: CP=\"ALL DSP COR PSAa PSDa OUR NOR ONL UNI COM NAV\"");
		setcookie("pirates_key", "", time()-3600);;
		setcookie("pirates_user_id", "", time()-3600);;
		
		
		
		
		////copy pasta
		
		
		
		
		
		
		
		
		
			//login to get the user id
	$MYSPACE_SECRET_KEY = 'CHANGEME';
	$remote_signature = $_REQUEST['oauth_signature'];
	//print_r($_REQUEST);
	
	$url = 
strtolower('http://dev.greenrobot.com/pirates/myspace/index.php');
	unset($_GET['oauth_signature']);
	ksort($_GET);
	$base_string = 'GET&'.
               urlencode($url).'&'.
               urlencode(http_build_query($_GET));
	$secret = $MYSPACE_SECRET_KEY.'&';
	
	$local_signature = base64_encode(hash_hmac("sha1", $base_string, $secret, TRUE));

	if ($remote_signature == $local_signature) {
	  //echo 'success';
	  $cookietime = time() + (3600 * 24 * 30);
	  $user_id = $_REQUEST['opensocial_viewer_id'];
	  $secret_cookie=md5( $user . $random_string ); 
	   
	  header("p3p: CP=\"ALL DSP COR PSAa PSDa OUR NOR ONL UNI COM NAV\"");
	  
	  if($user_id != -1 && $user_id != 0) {
	  	setcookie('pirates_key', $secret_cookie, $cookietime, "/");
	  	setcookie("pirates_user_id",  $user_id,  $cookietime, "/");
	  }
	  
	  $user = $user_id;
	 // echo $user;

	 }
	else {
		
		echo "<h1>There was a problem loading Pirates. Local Signature does not match remote signature.  Cookies are required in order to play. If you are using Safari make sure to select 'Always accept cookies' under security preferences.   Try enabling cookies, refreshing, clearing your cookies, or if you haven't installed Pirates <a href='http://myspace.com/piratewars'>install it</a> here.";
		
		echo "debug:";
		
		echo "request:";
		print_r($_REQUEST);
		echo "cookies:";
		print_r($_COOKIE);
		echo "local sig: $local_signature remote sig: $remote_signature";
		
		exit();
	}
		
		
		
		//end copy pasta
		
		
		
		
		
		
		
	//}
}

else if($pirates_user_id && $pirates_key) {
	$the_key = md5( $user . $random_string );
	if($pirates_key == $the_key) {
		//echo 'o';
		$user = $pirates_user_id;
		//echo $user;
	}
	else {
		//echo "pirates_key $pirates_key";
		//echo "pirates_user_id $pirates_user_id";
		//echo "the_key $the_key";
		
		echo "<h1>There was a problem loading Pirates.   Cookies are required in order to play. If you are using Safari make sure to select 'Always accept cookies' under security preferences.  Also try enabling cookies, refreshing, clearing your cookies, or if you haven't installed Pirates <a href='http://myspace.com/piratewars'>install it</a> here.";
		exit();
	}
	//echo "got the cookie";	
}
else {	//login to get the user id
	$MYSPACE_SECRET_KEY = 'CHANGEME';
	$remote_signature = $_REQUEST['oauth_signature'];
	//print_r($_REQUEST);
	
	$url = 
strtolower('http://dev.greenrobot.com/pirates/myspace/index.php');
	unset($_GET['oauth_signature']);
	ksort($_GET);
	$base_string = 'GET&'.
               urlencode($url).'&'.
               urlencode(http_build_query($_GET));
	$secret = $MYSPACE_SECRET_KEY.'&';
	
	$local_signature = base64_encode(hash_hmac("sha1", $base_string, $secret, TRUE));

	if ($remote_signature == $local_signature) {
	  //echo 'success';
	  $cookietime = time() + (3600 * 24 * 30);
	  $user_id = $_REQUEST['opensocial_viewer_id'];
	  $secret_cookie=md5( $user . $random_string ); 
	   
	  header("p3p: CP=\"ALL DSP COR PSAa PSDa OUR NOR ONL UNI COM NAV\"");
	  
	  if($user_id != -1 && $user_id != 0) {
	  	setcookie('pirates_key', $secret_cookie, $cookietime, "/");
	  	setcookie("pirates_user_id",  $user_id,  $cookietime, "/");
	  }
	  
	  $user = $user_id;
	 // echo $user;

	 }
	else {
		
		echo "<h1>There was a problem loading Pirates. Local Signature does not match remote signature.  Cookies are required in order to play. If you are using Safari make sure to select 'Always accept cookies' under security preferences.   Try enabling cookies, refreshing, clearing your cookies, or if you haven't installed Pirates <a href='http://myspace.com/piratewars'>install it</a> here.";
		
		echo "debug:";
		
		echo "request:";
		print_r($_REQUEST);
		echo "cookies:";
		print_r($_COOKIE);
		echo "local sig: $local_signature remote sig: $remote_signature";
		
		exit();
	}

}

}

?>
