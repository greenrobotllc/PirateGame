
<?php
require_once 'includes.php';
global $DB;

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
$r = $DB->Execute('update jokes set approved = 1 where id = ?', array($joke_id)); 

$question = $DB->GetOne('select question from jokes where id = ?', array($joke_id));
$answer = $DB->GetOne('select answer from jokes where id = ?', array($joke_id));
$user = $DB->GetOne('select user from jokes where id = ?', array($joke_id));

$r = $DB->Execute('insert into jokes_approved (question, answer, user, created_at) values(?, ?, ?, now())', array($question ,$answer, $user)); 


$r = $DB->Execute('update users set buried_coin_total = buried_coin_total + 200 where id = ?', array($joke_user));

//print_r($r);

$facebook->redirect('approve_jokes.php');
?>