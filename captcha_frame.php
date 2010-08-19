<?php
global $user;

error_reporting(E_ERROR | E_PARSE | E_WARNING);

require_once 'config_network.php';
require_once("adodb/adodb-exceptions.inc.php");
require_once("adodb/adodb.inc.php");
require_once 'memcache_wrapper.php';

	
if($network_id == 0) {
	$db_ip = '10.8.50.198';
	$db_ip = 'CHANGEME'; 
 
	//$db_user = 'CHANGEME';
	$db_user='piratewars';
	$db_pass='CHANGEME';
	//$db_pass = 'CHANGEME';
	$db_name = 'CHANGEME';
	

	$facebook_canvas_url = 'CHANGEME';
	$base_url = 'CHANGEME/pirates/fb';
	$base_url_captcha = 'http://gamesecuritywords.com/pirates/fb';


    $memcache_temp = new MemcacheWrapper(); //use temp for mile limits, gambing limits
    $memcache_temp->addServer('10.12.198.194', 11219);

    $memcache = new MemcacheWrapper();
    $memcache->addServer('10.12.198.194', 11211);

}
else {

	//$db_ip = '10.8.50.198';
	$db_ip = 'CHANGEME'; 
	$db_user = 'CHANGEME';
	$db_pass = 'CHANGEME';
	$db_name = 'CHANGEME';

	$facebook_canvas_url = 'http://dev.greenrobot.com/pirates/myspace';
	$base_url = $facebook_canvas_url;
	$base_url_captcha = 'http://gamesecuritywords.com/pirates/myspace';



    $memcache_temp = new MemcacheWrapper('mt'); //use temp for mile limits, gambing limits
    $memcache_temp->addServer('10.12.198.194', 11213);
    $memcache_temp->addServer('10.8.50.196', 11213);

    $memcache = new MemcacheWrapper('my');
    $memcache->addServer('10.12.198.194', 11211);

}

	$connect= "mysql://$db_user:$db_pass@$db_ip/$db_name?persist";
	$DB = NewADOConnection($connect);

function update_action2($user, $action) {
	global $DB, $memcache;
	if($memcache) {
    	$team = $memcache->set($user . ":a", $action);
    	$DB->Execute("update users set current_action='$action' where id = $user");
  	}
  	else {
  		$DB->Execute("update users set current_action='$action' where id = $user");	    
  	}  
}

if ($_POST["submit"]) {
  
  //include_once '../client/facebook.php';
  //include_once 'lib.php';
 //include_once 'config.php';
  $user = $_REQUEST['user'];
  $page = $_REQUEST['page'];
  $item = $_REQUEST['item'];

  require_once('recaptchalib.php');
  if($network_id == 0) {
	$privatekey = '6LfN1AAAAAAAANNTEhf1pqAHUKim9tlkkYZH88d5';
  }
  else {
	//$privatekey = '6LfN1AAAAAAAANNTEhf1pqAHUKim9tlkkYZH88d5';
  	$privatekey = '6LeF6wIAAAAAAJZd605m48lwKLYACX2uWOGFyXbB';
  }
  $resp = recaptcha_check_answer ($privatekey,
                                  $_SERVER["REMOTE_ADDR"],
                                  $_POST["recaptcha_challenge_field"],
                                  $_POST["recaptcha_response_field"]);
	
  if ($resp->is_valid) {
    echo "<center><h1>You got it!</h1>";
	if($page != "") {
		if($item != "") {
			update_action2($user, "captcha_complete");
			$url = "$facebook_canvas_url/$page" . "?item=$item";
			if($network_id == 0) {
				echo "<a target ='_parent' href='$facebook_canvas_url/$page?item=$item'>click here to continue!</a></center>";
			}
			else {
				echo "<a target='top' href='http://profile.myspace.com/Modules/Applications/Pages/Canvas.aspx?appId=100506'>click here to continue!</a></center>";		
			}
			//$facebook->redirect("$url");
		}
		else {
			update_action2($user, "captcha_complete");
			//$facebook->redirect("$facebook_canvas_url/$page");
			if($network_id == 0) {
				echo "<a target ='_parent' href='$facebook_canvas_url/$page'>click here to continue!</a></center>";
			}
			else {
				echo "<a target='top' href='http://profile.myspace.com/Modules/Applications/Pages/Canvas.aspx?appId=100506'>click here to continue!</a></center>";
			}
		}
	}
	else {
		update_action2($user, "captcha_complete");
		if($network_id == 0) {
			echo "<a target ='_parent' href='$facebook_canvas_url/index.php'>click here to continue!</a></center>";
		}
		else {
			echo "<a target='top' href='http://profile.myspace.com/Modules/Applications/Pages/Canvas.aspx?appId=100506'>click here to continue!</a></center>";		
		}
		//$facebook->redirect("$facebook_canvas_url/index.php");
	}



    # in a real application, you should send an email, create an account, etc
  } else {
    # set the error code so that we can display it. You could also use
    die ("<center>You entered the words incorrectly, <a href='captcha_frame.php?user=$user&page=$page&item=$item'>please try again</a></center>"); //, but using the error message is
    # more user friendly
    $error = $resp->error;
  }
}
else {
$user = $_REQUEST['fb_sig_user'];
if(empty($user)) {
	$user = $_REQUEST['user'];
}

?>
<html>
<head>
</head>
<body>

<center>
<?php
/*
<form method='post' action='<?php echo $base_url_captcha; ?>/captcha_frame.php'>?>
*/
?>
<form method='post'>

<?php
global $facebook, $facebook_canvas_url;
$page = urldecode($_REQUEST['page']);
$item = $_REQUEST['item'];

require_once('recaptchalib.php');
//print_r($_REQUEST);

if($network_id == 0) {
	$publickey = '6LfN1AAAAAAAAPxTPe-y3p153BvROilukfIt4sb9'; 
}
else {
	//$publickey = '6LfN1AAAAAAAAPxTPe-y3p153BvROilukfIt4sb9'; 

	$publickey = '6LeF6wIAAAAAAO9RIJpDkWrs7ayt08Ooo5DxOhT4';
}
// you got this from the signup page
echo recaptcha_get_html($publickey);


?>

<input type='submit' value='submit' name='submit'>
<input type='hidden' value='<?php echo $page; ?>' name='page'>
<input type='hidden' value='<?php echo $item; ?>' name='item'>
<input type='hidden' value='<?php echo $user; ?>' name='user'>
</center>

</form>

</body>
</html>

<?php

}

?>
