
<?php
include_once '../client/facebook.php';
include_once 'lib.php';
include_once 'config.php';
global $DB;
require_once 'header.php';

//print_r($_REQUEST);
//echo $user;

$joke_id = $_REQUEST['joke_id'];
$joke_user = $_REQUEST['joke_user'];


if($user != 1807687 && $user != 9324337) {
	$facebook->redirect('index.php');
}


if(!$joke_id) {
	$facebook->redirect('approve_jokes.php');

}
$r = $DB->Execute('update jokes set approved = 2 where id = ?', array($joke_id)); 

//$r = $DB->Execute('update users set buried_coin_total = buried_coin_total + 200 where id = ?', array($joke_user));

//print_r($r);

$facebook->redirect('approve_jokes.php');
?>