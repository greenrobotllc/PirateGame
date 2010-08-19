
<?php
include_once '../client/facebook.php';
include_once 'lib.php';
include_once 'config.php';
global $DB;
require_once 'header.php';

//print_r($_REQUEST);

$question = $_REQUEST['question'];
$answer = $_REQUEST['answer'];

global $DB, $facebook;

$DB->Execute('insert into jokes (question, answer, user, created_at) values(?, ?, ?, now())', array($question, $answer, $user));


$facebook->redirect('index.php?msg=joke-added');
?>

