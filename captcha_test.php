<?php

include_once '../client/facebook.php';
// some basic library functions
include_once 'lib.php';
// this defines some of your basic setup
include_once 'config.php';
include_once 'captcha_arrays.php';
require_once 'fbexchange.php';

global $DB;

$user_in_db = is_user_in_db($user);
if($user_in_db ==0) {
	$facebook->redirect("$facebook_canvas_url/install.php");
}

update_action($user, "captcha");

$facebook->redirect("$facebook_canvas_url/captcha_page.php?page=harbor.php");

?>
