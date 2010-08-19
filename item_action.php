<?php
ob_start();

require_once 'includes.php';
global $DB;



//print_r($user);
$user_in_db = is_user_in_db($user);
if($user_in_db ==0) {
	$facebook->redirect("$facebook_canvas_url/install.php");
}

$coin_total = get_coin_total($user);
$buried_coin_total = get_coin_total_buried($user);

$level = get_level($user);

//$how_many_bombs = get_how_many_bombs($user);

//print dashboard();

$item = $_REQUEST['item'];

if($item == "rum") {

	$action = get_current_action($user);
	$no_captcha = get_no_captcha();
	$pass = false;
	if (in_array($user, $no_captcha )) {
	   $pass = true;
    }
	//echo "action $action pass $pass";
	//exit();
	if($action != "captcha_complete" && $pass == false) {
		update_action($user, "captcha");
		$facebook->redirect("$facebook_canvas_url/captcha_page.php?page=item_action.php&item=rum");
	}
	else {  //this means they've completed the captcha
		update_action($user, "NULL");
	}

	$success = drink_rum($user);
	
	if($success == true) {
		
		//echo 'hello';
		$facebook->redirect("index.php?msg=drank-rum");
	}
	else {
		$facebook->redirect("index.php");
	}
}
else if($item == "ham") {
	$success = eat_ham($user);
	
	if($success == true) {
		$facebook->redirect("index.php?msg=eat-ham");
	}
	else {
		$facebook->redirect("index.php");
	}
}
else {
	$facebook->redirect("index.php");
}

?>